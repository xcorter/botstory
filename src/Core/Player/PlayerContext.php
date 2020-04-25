<?php

namespace App\Core\Player;

class PlayerContext
{
    /**
     * @var int
     */
    private $mode = PlayerConstant::MODE_START;
    /**
     * @var int|null
     */
    private $currentGame;
    /**
     * @var int|null
     */
    private $questionId;

    public function selectGameMode(): PlayerContext
    {
        $this->mode = PlayerConstant::MODE_SELECT_GAME;
        return $this;
    }

    public function showMenuMode(): PlayerContext
    {
        $this->mode = PlayerConstant::MODE_MENU;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStart(): bool
    {
        return $this->mode === PlayerConstant::MODE_START;
    }

    public function isModeShowMenu(): bool
    {
        return $this->mode === PlayerConstant::MODE_MENU;
    }

    public function isModeSelectGame(): bool
    {
        return $this->mode === PlayerConstant::MODE_SELECT_GAME;
    }

    public function isModeGameOver(): bool
    {
        return $this->mode === PlayerConstant::MODE_GAME_OVER;
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
        $this->mode = PlayerConstant::MODE_RUN;
    }

    public function backToGame(): void
    {
        $this->mode = PlayerConstant::MODE_RUN;
    }

    public function isGameRunning(): bool
    {
        return $this->mode === PlayerConstant::MODE_RUN;
    }

    public function gameOver(): void
    {
        $this->mode = PlayerConstant::MODE_GAME_OVER;
    }

    public function selectSettingsMenu(): void
    {
        $this->mode = PlayerConstant::MODE_SETTINGS;
    }

    public function isSettingMenu():bool
    {
        return $this->mode === PlayerConstant::MODE_SETTINGS;
    }

    public function serialize(): string
    {
        return serialize($this);
    }

    public static function deserialize($serialized): PlayerContext
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
