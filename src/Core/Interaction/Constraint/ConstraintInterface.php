<?php

namespace App\Core\Interaction\Constraint;

use App\Core\Game\Entity\GameContext;

interface ConstraintInterface
{
    public function isSatisfiedBy(GameContext $gameContext): bool;
}
