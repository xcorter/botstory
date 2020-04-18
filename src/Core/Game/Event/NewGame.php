<?php

namespace App\Core\Game\Event;

use App\Core\Game\Entity\Game;
use Symfony\Contracts\EventDispatcher\Event;

class NewGame extends Event
{
    public const NAME = 'game.new';

    /**
     * @var Game
     */
    private $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function getGame(): Game
    {
        return $this->game;
    }
}