<?php

namespace App\Web\DTO;

use App\Core\Game\Entity\Game;
use App\Core\User\Entity\User;

class GameDTO
{
    public $name;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function toEntity()
    {
        return new Game($this->name, $this->user);
    }
}