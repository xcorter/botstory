<?php

namespace App\Core\Repository;

use App\Core\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;

class GameRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * GameRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Game[]
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Game::class)->findAll();
    }

    /**
     * @param int $id
     * @return Game|null
     */
    public function findGameById(int $id): ?Game
    {
        return $this->entityManager->getRepository(Game::class)->find($id);
    }
}
