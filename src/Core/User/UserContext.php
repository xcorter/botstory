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
    /**
     * @var int|null
     */
    private $scriptId;

    public function selectGameStep(): UserContext
    {
        $this->step = UserConstant::STEP_SELECT_GAME;
        return $this;
    }

    public function showMenuStep(): UserContext
    {
        $this->step = UserConstant::STEP_MENU;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStart(): bool
    {
        return $this->step === UserConstant::STEP_START;
    }

    public function isStepShowMenu(): bool
    {
        return $this->step === UserConstant::STEP_MENU;
    }

    public function isStepSelectGame(): bool
    {
        return $this->step === UserConstant::STEP_SELECT_GAME;
    }

    public function runGame(int $game): void
    {
        $this->currentGame = $game;
        $this->step = UserConstant::STEP_RUN;
    }

    public function isGameRunning(): bool
    {
        return $this->step === UserConstant::STEP_RUN;
    }

    public function serialize(): string
    {
        return serialize($this);
    }

    public static function deserialize($serialized): UserContext
    {
        return unserialize($serialized, [__CLASS__]);
    }

    public function setCurrentScript(int $scriptId): void
    {
        $this->scriptId = $scriptId;
    }

    /**
     * @return int|null
     */
    public function getCurrentScript(): ?int
    {
        return $this->scriptId;
    }

    /**
     * @return int|null
     */
    public function getCurrentGame(): ?int
    {
        return $this->currentGame;
    }
}
