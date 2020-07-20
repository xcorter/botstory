<?php

namespace App\Bot\Telegram\Console;

use App\Core\Mode\ModeFactory;
use App\Core\Entity\UpdateLog;
use App\Core\Entity\Player;
use App\Core\Update\UpdateRepository;
use App\Core\Player\PlayerConstant;
use App\Core\Player\PlayerRepository;
use Psr\Log\LoggerInterface;
use SimpleTelegramBotClient\Exception\ClientException;
use SimpleTelegramBotClient\TelegramService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SimpleTelegramBotClient\Dto\Type\User as TelegramUser;
use Throwable;

class TelegramBotRun extends Command
{
    /**
     * @var TelegramService
     */
    private $telegramService;
    /**
     * @var PlayerRepository
     */
    private $userRepository;
    /**
     * @var UpdateRepository
     */
    private $updateRepository;
    /**
     * @var ModeFactory
     */
    private $stepFactory;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * TelegramBotRun constructor.
     * @param TelegramService $telegramService
     * @param PlayerRepository $userRepository
     * @param UpdateRepository $updateRepository
     * @param ModeFactory $stepFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        TelegramService $telegramService,
        PlayerRepository $userRepository,
        UpdateRepository $updateRepository,
        ModeFactory $stepFactory,
        LoggerInterface $logger
    ) {
        parent::__construct();
        $this->telegramService = $telegramService;
        $this->userRepository = $userRepository;
        $this->updateRepository = $updateRepository;
        $this->stepFactory = $stepFactory;
        $this->logger = $logger;
    }


    protected function configure()
    {
        $this
            ->setName('bot:telegram:run')
            ->setDescription('Запустить бота телеграма');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Telegram bot is running');
        while (true) {
            $response = $this->telegramService->getUpdates();
            $updates = $response->getResult();
            foreach ($updates as $update) {
                $updateId = $update->getUpdateId();
                if ($this->updateRepository->updateExists($updateId, PlayerConstant::PROVIDER_TELEGRAM)) {
                    continue;
                }
                echo $updateId . "\n";
                $message = $update->getMessage();
                if (!$message) {
                    continue;
                }
                $userFromTelegram = $message->getFrom();
                if (!$userFromTelegram) {
                    throw new \RuntimeException('Player from telegram is null');
                }

                $telegramUserId = (string) $userFromTelegram->getId();
                $user = $this->userRepository->findProviderUserId($telegramUserId, PlayerConstant::PROVIDER_TELEGRAM);
                if (!$user) {
                    $user = $this->createNewUser($userFromTelegram);
                }
                try {
                    $step = $this->stepFactory->getStep($user, $message);
                } catch (Throwable $exception) {
                    $this->logger->error($exception->getMessage());
                    continue;
                }

                try {
                    $step->run($user, $message);
                } catch (ClientException $exception) {
                    $response = $exception->getResponse();
                    if ($response && $response->getErrorCode() === 403) {
                        // TODO user blocks the bot
                        continue;
                    }
                }

                $this->createUpdate($update->getUpdateId());
                $this->userRepository->save($user);
            }
            sleep(1);
        }
    }

    /**
     * @param TelegramUser $userFromTelegram
     * @return Player
     */
    private function createNewUser(TelegramUser $userFromTelegram): Player
    {
        $telegramUserId = (string) $userFromTelegram->getId();
        $user = new Player($telegramUserId, PlayerConstant::PROVIDER_TELEGRAM);
        $user
            ->setFirstName($userFromTelegram->getFirstName())
            ->setLastName($userFromTelegram->getLastName())
            ->setUsername($userFromTelegram->getUsername())
        ;
        $this->userRepository->save($user);
        return $user;
    }

    private function createUpdate(int $updateId): void
    {
        $update = new UpdateLog($updateId, PlayerConstant::PROVIDER_TELEGRAM);
        $this->updateRepository->save($update);
    }
}
