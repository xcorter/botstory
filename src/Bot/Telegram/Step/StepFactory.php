<?php

namespace App\Bot\Telegram\Step;

use App\Core\Entity\User;
use App\Core\Interaction\Command;
use Psr\Log\LoggerInterface;
use SimpleTelegramBotClient\Dto\Message;

class StepFactory
{
    /**
     * @var ShowMenuStep
     */
    private $showMenuStep;
    /**
     * @var SelectGameStep
     */
    private $selectGameStep;
    /**
     * @var RunGameStep
     */
    private $runGameStep;
    /**
     * @var GameSelectedStep
     */
    private $gameSelectedStep;
    /**
     * @var SettingsStep
     */
    private $settingsStep;
    /**
     * @var ResetGameStep
     */
    private $resetGameStep;
    /**
     * @var BackToGameStep
     */
    private $backToGameStep;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * StepFactory constructor.
     * @param ShowMenuStep $showMenuStep
     * @param SelectGameStep $selectGameStep
     * @param RunGameStep $runGameStep
     * @param GameSelectedStep $gameSelectedStep
     * @param SettingsStep $settingsStep
     * @param ResetGameStep $resetGameStep
     * @param BackToGameStep $backToGameStep
     * @param LoggerInterface $logger
     */
    public function __construct(
        ShowMenuStep $showMenuStep,
        SelectGameStep $selectGameStep,
        RunGameStep $runGameStep,
        GameSelectedStep $gameSelectedStep,
        SettingsStep $settingsStep,
        ResetGameStep $resetGameStep,
        BackToGameStep $backToGameStep,
        LoggerInterface $logger
    ) {
        $this->showMenuStep = $showMenuStep;
        $this->selectGameStep = $selectGameStep;
        $this->runGameStep = $runGameStep;
        $this->gameSelectedStep = $gameSelectedStep;
        $this->settingsStep = $settingsStep;
        $this->resetGameStep = $resetGameStep;
        $this->backToGameStep = $backToGameStep;
        $this->logger = $logger;
    }

    /**
     * @param User $user
     * @param Message $message
     * @return BackToGameStep|GameSelectedStep|ResetGameStep|RunGameStep|SelectGameStep|SettingsStep|ShowMenuStep
     */
    public function getStep(User $user, Message $message)
    {
        if ($message->getText() === Command::SETTINGS) {
            return $this->settingsStep;
        } elseif ($message->getText() === Command::RESET_GAME) {
            return $this->resetGameStep;
        } elseif ($message->getText() === Command::BACK_TO_GAME) {
            return $this->backToGameStep;
        }
        if ($user->getContext()->isStart()) {
            return $this->showMenuStep;
        } elseif ($user->getContext()->isStepShowMenu()) {
            return $this->selectGameStep;
        } elseif ($user->getContext()->isStepSelectGame()) {
            return $this->gameSelectedStep;
        } elseif ($user->getContext()->isGameRunning()) {
            return $this->runGameStep;
        }
        $this->logger->error('Step not found', [
            'userId' => $user->getId()
        ]);
        throw new \OutOfBoundsException('Step not found');
    }
}
