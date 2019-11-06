<?php

namespace App\Core\Interaction\Action;

use App\Core\Game\Entity\GameContext;

interface ActionInterface
{
    /**
     * @param GameContext $gameContext
     * @return void
     */
    public function execute(GameContext $gameContext): void;
}
