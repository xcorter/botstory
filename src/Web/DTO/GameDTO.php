<?php

namespace App\Web\DTO;

use App\Core\Game\Entity\Game;

class GameDTO
{
    public $name;

    public function toEntity()
    {
        return new Game($this->name, '');
    }
}