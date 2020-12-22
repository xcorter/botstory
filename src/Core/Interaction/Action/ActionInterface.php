<?php

namespace App\Core\Interaction\Action;

use App\Core\Game\Entity\GameContext;

interface ActionInterface
{
    public function execute(GameContext $gameContext): void;
}
