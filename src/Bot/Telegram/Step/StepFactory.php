<?php

namespace App\Bot\Telegram\Step;

use App\Core\Entity\User;
use Psr\Log\LoggerInterface;

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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * StepFactory constructor.
     * @param ShowMenuStep $showMenuStep
     * @param SelectGameStep $selectGameStep
     * @param RunGameStep $runGameStep
     * @param GameSelectedStep $gameSelectedStep
     * @param LoggerInterface $logger
     */
    public function __construct(
        ShowMenuStep $showMenuStep,
        SelectGameStep $selectGameStep,
        RunGameStep $runGameStep,
        GameSelectedStep $gameSelectedStep,
        LoggerInterface $logger
    ) {
        $this->showMenuStep = $showMenuStep;
        $this->selectGameStep = $selectGameStep;
        $this->runGameStep = $runGameStep;
        $this->gameSelectedStep = $gameSelectedStep;
        $this->logger = $logger;
    }

    /**
     * @param User $user
     * @return GameSelectedStep|RunGameStep|SelectGameStep|ShowMenuStep
     */
    public function getStep(User $user)
    {
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
