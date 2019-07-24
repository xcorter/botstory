<?php

namespace App\Bot\Telegram\Step;

use App\Core\Entity\User;

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
     * StepFactory constructor.
     * @param ShowMenuStep $showMenuStep
     * @param SelectGameStep $selectGameStep
     * @param RunGameStep $runGameStep
     * @param GameSelectedStep $gameSelectedStep
     */
    public function __construct(
        ShowMenuStep $showMenuStep,
        SelectGameStep $selectGameStep,
        RunGameStep $runGameStep,
        GameSelectedStep $gameSelectedStep
    ) {
        $this->showMenuStep = $showMenuStep;
        $this->selectGameStep = $selectGameStep;
        $this->runGameStep = $runGameStep;
        $this->gameSelectedStep = $gameSelectedStep;
    }


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
        throw new \RuntimeException('');
    }
}
