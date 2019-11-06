<?php

namespace App\Core\Mode;

use App\Core\Entity\User;
use App\Core\Interaction\Command;
use Psr\Log\LoggerInterface;
use SimpleTelegramBotClient\Dto\Type\Message;

class ModeFactory
{
    /**
     * @var ShowMenuMode
     */
    private $showMenuStep;
    /**
     * @var SelectGameMode
     */
    private $selectGameMode;
    /**
     * @var RunGameMode
     */
    private $runGameMode;
    /**
     * @var GameSelectedMode
     */
    private $gameSelectedMode;
    /**
     * @var SettingsMode
     */
    private $settingsMode;
    /**
     * @var ResetGameMode
     */
    private $resetGameMode;
    /**
     * @var BackToGameMode
     */
    private $backToGameMode;
    /**
     * @var GameOverMode
     */
    private $gameOverMode;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ModeFactory constructor.
     * @param ShowMenuMode $showMenuStep
     * @param SelectGameMode $selectGameMode
     * @param RunGameMode $runGameMode
     * @param GameSelectedMode $gameSelectedMode
     * @param SettingsMode $settingsMode
     * @param ResetGameMode $resetGameMode
     * @param BackToGameMode $backToGameMode
     * @param GameOverMode $gameOverMode
     * @param LoggerInterface $logger
     */
    public function __construct(ShowMenuMode $showMenuStep, SelectGameMode $selectGameMode, RunGameMode $runGameMode, GameSelectedMode $gameSelectedMode, SettingsMode $settingsMode, ResetGameMode $resetGameMode, BackToGameMode $backToGameMode, GameOverMode $gameOverMode, LoggerInterface $logger)
    {
        $this->showMenuStep = $showMenuStep;
        $this->selectGameMode = $selectGameMode;
        $this->runGameMode = $runGameMode;
        $this->gameSelectedMode = $gameSelectedMode;
        $this->settingsMode = $settingsMode;
        $this->resetGameMode = $resetGameMode;
        $this->backToGameMode = $backToGameMode;
        $this->gameOverMode = $gameOverMode;
        $this->logger = $logger;
    }

    /**
     * @param User $user
     * @param Message $message
     * @return ModeInterface
     */
    public function getStep(User $user, Message $message): ModeInterface
    {
        if ($message->getText() === Command::SETTINGS) {
            return $this->settingsMode;
        } elseif ($message->getText() === Command::RESET_GAME) {
            return $this->resetGameMode;
        } elseif ($message->getText() === Command::BACK_TO_GAME) {
            return $this->backToGameMode;
        } elseif ($message->getText() === Command::SHOW_ALL_GAMES) {
            return $this->selectGameMode;
        }

        if ($user->getContext()->isStart()) {
            return $this->showMenuStep;
        } elseif ($user->getContext()->isModeShowMenu()) {
            return $this->selectGameMode;
        } elseif ($user->getContext()->isModeSelectGame()) {
            return $this->gameSelectedMode;
        } elseif ($user->getContext()->isModeGameOver()) {
            return $this->gameOverMode;
        } elseif ($user->getContext()->isGameRunning()) {
            return $this->runGameMode;
        }
        $this->logger->error('Mode not found', [
            'userId' => $user->getId()
        ]);
        throw new \OutOfBoundsException('Mode not found');
    }
}
