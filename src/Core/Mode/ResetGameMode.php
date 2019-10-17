<?php

namespace App\Core\Mode;

use App\Core\Entity\User;
use SimpleTelegramBotClient\Dto\Type\Message;

class ResetGameMode implements ModeInterface
{
    /**
     * @var RunGameMode
     */
    private $runGameStep;

    /**
     * ResetGameStep constructor.
     * @param RunGameMode $runGameStep
     */
    public function __construct(RunGameMode $runGameStep)
    {
        $this->runGameStep = $runGameStep;
    }

    public function run(User $user, Message $message): void
    {
        $user->resetScriptId();
        $this->runGameStep->run($user, $message);
    }
}
