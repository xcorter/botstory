<?php

namespace App\Core\Interaction\Constraint;

use App\Core\Game\Entity\GameContext;

class ConstraintComposite implements ConstraintInterface
{
    /**
     * @var ConstraintInterface[]
     */
    private $constraints = [];

    public function isSatisfiedBy(GameContext $gameContext): bool
    {
        foreach ($this->constraints as $constraint) {
            if (!$constraint->isSatisfiedBy($gameContext)) {
                return false;
            }
        }

        return true;
    }

    public function addConstraint(ConstraintInterface $constraint): self
    {
        $this->constraints[] = $constraint;
        return $this;
    }
}
