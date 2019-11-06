<?php

namespace App\Core\Game;

use App\Core\Entity\User;
use App\Core\Game\Entity\Game;
use App\Core\Game\Entity\GameContext;

interface GameContextRepositoryInterface
{
    public function save(GameContext $gameContext): void;
    public function findGameContext(User $user, Game $game): ?GameContext;
    public function delete(GameContext $gameContext): void;
}
