<?php

namespace App\Core\Game;

use App\Core\Game\Entity\Game;
use App\Core\Game\Entity\GameConstraint;

interface GameConstraintRepositoryInterface
{
    /**
     * @param Game $game
     * @return GameConstraint[]
     */
    public function findConstraints(Game $game): array;
}
