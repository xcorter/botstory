<?php

namespace App\Core\Mode;

use App\Core\Entity\User;
use SimpleTelegramBotClient\Dto\Type\Message;

class BackToGameMode implements ModeInterface
{
    /**
     * @var RunGameMode
     */
    private $runGameStep;

    /**
     * BackToGameStep constructor.
     * @param RunGameMode $runGameStep
     */
    public function __construct(RunGameMode $runGameStep)
    {
        $this->runGameStep = $runGameStep;
    }

    public function run(User $user, Message $message): void
    {
        $user->backToGame();
        $this->runGameStep->run($user, $message);
    }

}
