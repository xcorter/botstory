<?php

namespace App\Core\Step;

use App\Core\Entity\User;
use SimpleTelegramBotClient\Dto\Type\Message;

class BackToGameStep implements StepInterface
{
    /**
     * @var RunGameStep
     */
    private $runGameStep;

    /**
     * BackToGameStep constructor.
     * @param RunGameStep $runGameStep
     */
    public function __construct(RunGameStep $runGameStep)
    {
        $this->runGameStep = $runGameStep;
    }

    public function run(User $user, Message $message): void
    {
        $user->backToGame();
        $this->runGameStep->run($user, $message);
    }

}
