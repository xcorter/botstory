<?php

namespace App\Tests\Utils;

use App\Core\Entity\Player;
use App\Core\Game\Entity\Game;
use App\Core\Game\Entity\GameContext;
use App\Core\User\Entity\User;
use PHPUnit\Framework\TestCase;

class GameContextFactory extends TestCase
{

    public static function createGameContext(array $context): GameContext
    {
        $user = new User();
        $game = new Game('gg', $user);
        $player = new Player('1','1');
        return new GameContext($player, $game, json_encode($context));
    }
}