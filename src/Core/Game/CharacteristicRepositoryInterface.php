<?php

namespace App\Core\Game;

use App\Core\Game\Entity\Characteristic;
use App\Core\Game\Entity\Game;

interface CharacteristicRepositoryInterface
{
    /**
     * @param Game $game
     * @return Characteristic[]
     */
    public function findByGame(Game $game): array;
}
