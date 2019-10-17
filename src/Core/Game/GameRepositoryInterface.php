<?php

namespace App\Core\Game;

use App\Core\Game\Entity\Game;

interface GameRepositoryInterface
{
    /**
     * @return Game[]
     */
    public function findAll(): array;

    /**
     * @param string $name
     * @return Game|null
     */
    public function findGameByName(string $name): ?Game;

    /**
     * @param int $id
     * @return Game
     */
    public function findById(int $id): Game;
}
