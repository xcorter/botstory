<?php

namespace App\Bot\Console;

use App\Core\CommandProcessor\DummyCommandProcessor;
use App\Core\Interaction\InteractionService;
use App\Core\User\UserContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DummyClient extends Command
{
    /**
     * @var InteractionService
     */
    private $interactionService;

    /**
     * DummyClient constructor.
     * @param InteractionService $interactionService
     */
    public function __construct(InteractionService $interactionService)
    {
        parent::__construct();
        $this->interactionService = $interactionService;
    }

    protected function configure(): void
    {
        $this
            ->setName('bot:dummy:run')
            ->setDescription('Запустить бота');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $interactionResponse = $this->interactionService->getInfo();
        $userContext = new UserContext();
        $output->writeln($interactionResponse->getText());
        $command = fgets(STDIN, 4096);


    }
}
