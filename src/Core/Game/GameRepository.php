<?php

namespace App\Core\Game;

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
     * @param string $name
     * @return Game|null
     */
    public function findGameByName(string $name): ?Game
    {
        return $this->entityManager->getRepository(Game::class)->findOneBy([
            'name' => $name
        ]);
    }

    /**
     * @param int $id
     * @return Game
     */
    public function findById(int $id): Game
    {
        return $this->entityManager->getRepository(Game::class)->find($id);
    }
}
