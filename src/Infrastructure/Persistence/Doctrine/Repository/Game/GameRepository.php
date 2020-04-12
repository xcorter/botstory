<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Game;

use App\Core\Game\Entity\Game;
use App\Core\Game\GameRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class GameRepository implements GameRepositoryInterface
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

    /**
     * @param Game $game
     * @return void
     */
    public function save(Game $game): void
    {
        $this->entityManager->persist($game);
        $this->entityManager->flush();
    }
}
