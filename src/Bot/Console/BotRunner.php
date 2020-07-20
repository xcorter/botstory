<?php

namespace App\Bot\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BotRunner extends Command
{

    protected function configure()
    {
        $this
            ->setName('bot:run')
            ->setDescription('Запустить бота');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('I`m running');
        return 0;
    }
}
