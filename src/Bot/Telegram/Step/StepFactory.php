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
     * StepFactory constructor.
     * @param ShowMenuStep $showMenuStep
     * @param SelectGameStep $selectGameStep
     */
    public function __construct(ShowMenuStep $showMenuStep, SelectGameStep $selectGameStep)
    {
        $this->showMenuStep = $showMenuStep;
        $this->selectGameStep = $selectGameStep;
    }

    public function getStep(User $user)
    {
        if ($user->getContext()->isStart()) {
            return $this->showMenuStep;
        } elseif ($user->getContext()->isStepSelectGame()) {
            return $this->selectGameStep;
        }
        throw new \RuntimeException('');
    }
}
