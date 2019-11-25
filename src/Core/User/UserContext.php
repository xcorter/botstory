<?php

namespace App\Core\User;

class UserContext
{
    /**
     * @var int
     */
    private $mode = UserConstant::MODE_START;
    /**
     * @var int|null
     */
    private $currentGame;
    /**
     * @var int|null
     */
    private $questionId;

    public function selectGameMode(): UserContext
    {
        $this->mode = UserConstant::MODE_SELECT_GAME;
        return $this;
    }

    public function showMenuMode(): UserContext
    {
        $this->mode = UserConstant::MODE_MENU;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStart(): bool
    {
        return $this->mode === UserConstant::MODE_START;
    }

    public function isModeShowMenu(): bool
    {
        return $this->mode === UserConstant::MODE_MENU;
    }

    public function isModeSelectGame(): bool
    {
        return $this->mode === UserConstant::MODE_SELECT_GAME;
    }

    public function isModeGameOver(): bool
    {
        return $this->mode === UserConstant::MODE_GAME_OVER;
    }

    /**
     * @return int|null
     */
    public function getCurrentGame(): ?int
    {
        return $this->currentGame;
    }

    public function runGame(int $game): void
    {
        $this->currentGame = $game;
        $this->mode = UserConstant::MODE_RUN;
    }

    public function backToGame(): void
    {
        $this->mode = UserConstant::MODE_RUN;
    }

    public function isGameRunning(): bool
    {
        return $this->mode === UserConstant::MODE_RUN;
    }

    public function gameOver(): void
    {
        $this->mode = UserConstant::MODE_GAME_OVER;
    }

    public function selectSettingsMenu(): void
    {
        $this->mode = UserConstant::MODE_SETTINGS;
    }

    public function isSettingMenu():bool
    {
        return $this->mode === UserConstant::MODE_SETTINGS;
    }

    public function serialize(): string
    {
        return serialize($this);
    }

    public static function deserialize($serialized): UserContext
    {
        return unserialize($serialized, [__CLASS__]);
    }

    public function setCurrentQuestion(int $questionId): void
    {
        $this->questionId = $questionId;
    }

    /**
     * @return int|null
     */
    public function getCurrentQuestion(): ?int
    {
        return $this->questionId;
    }

    public function resetQuestionId(): void
    {
        $this->questionId = null;
    }
}
