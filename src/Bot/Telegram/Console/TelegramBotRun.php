<?php

namespace App\Bot\Telegram\Console;

use Doctrine\ORM\EntityManagerInterface;
use SimpleTelegramBotClient\TelegramService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Longman\TelegramBot\Telegram;

class TelegramBotRun extends Command
{
    /**
     * @var TelegramService
     */
    private $telegramService;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * TelegramBotRun constructor.
     * @param TelegramService $telegramService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(TelegramService $telegramService, EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('bot:telegram:run')
            ->setDescription('Запустить бота телеграма');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while (true) {
            $response = $this->telegramService->getUpdates();
            $updates = $response->getResult();
            foreach ($updates as $update) {
                $update->getMessage();
            }
            sleep(1);
        }
    }
}
