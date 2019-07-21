<?php

namespace App\Core\User;

class UserContext
{
    /**
     * @var int
     */
    private $step = UserConstant::STEP_START;
    /**
     * @var int
     */
    private $currentGame;

    public function selectGameStep(): UserContext
    {
        $this->step = UserConstant::STEP_SELECT_GAME;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStart(): bool
    {
        return $this->step === UserConstant::STEP_START;
    }

    public function isStepSelectGame(): bool
    {
        return $this->step === UserConstant::STEP_SELECT_GAME;
    }

    public function selectGame(int $game): void
    {
        $this->currentGame = $game;
    }

    public function serialize(): string
    {
        return serialize($this);
    }

    public static function deserialize($serialized): UserContext
    {
        return unserialize($serialized, [__CLASS__]);
    }
}
