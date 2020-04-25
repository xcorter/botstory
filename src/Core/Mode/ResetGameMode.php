<?php

namespace App\Core\Mode;

use App\Core\Entity\Player;
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

    public function run(Player $user, Message $message): void
    {
        $user->resetQuestionId();
        $this->runGameStep->run($user, $message);
    }
}
