<?php

namespace App\Bot\Telegram\Console;

use App\Bot\Telegram\Step\StepFactory;
use App\Core\Entity\UpdateLog;
use App\Core\Entity\User;
use App\Core\Update\UpdateRepository;
use App\Core\User\UserConstant;
use App\Core\User\UserRepository;
use Psr\Log\LoggerInterface;
use SimpleTelegramBotClient\Exception\ClientException;
use SimpleTelegramBotClient\TelegramService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SimpleTelegramBotClient\Dto\Type\User as TelegramUser;

class TelegramBotRun extends Command
{
    /**
     * @var TelegramService
     */
    private $telegramService;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UpdateRepository
     */
    private $updateRepository;
    /**
     * @var StepFactory
     */
    private $stepFactory;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * TelegramBotRun constructor.
     * @param TelegramService $telegramService
     * @param UserRepository $userRepository
     * @param UpdateRepository $updateRepository
     * @param StepFactory $stepFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        TelegramService $telegramService,
        UserRepository $userRepository,
        UpdateRepository $updateRepository,
        StepFactory $stepFactory,
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
                if ($this->updateRepository->updateExists($updateId, UserConstant::PROVIDER_TELEGRAM)) {
                    continue;
                }
                $message = $update->getMessage();
                if (!$message) {
                    continue;
                }
                $userFromTelegram = $message->getFrom();
                if (!$userFromTelegram) {
                    throw new \RuntimeException('User from telegram is null');
                }
                // TODO выпилить
                if ($userFromTelegram->getUsername() === 'kentforth') {
                    continue;
                }
                $user = $this->userRepository->findProviderUserId($userFromTelegram->getId(), UserConstant::PROVIDER_TELEGRAM);
                if (!$user) {
                    $user = $this->createNewUser($userFromTelegram);
                }
                try {
                    $step = $this->stepFactory->getStep($user, $message);
                } catch (\Throwable $exception) {
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
     * @return User
     */
    private function createNewUser(TelegramUser $userFromTelegram): User
    {
        $user = new User($userFromTelegram->getId(), UserConstant::PROVIDER_TELEGRAM);
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
        $update = new UpdateLog($updateId, UserConstant::PROVIDER_TELEGRAM);
        $this->updateRepository->save($update);
    }
}
