<?php

namespace App\Core\Repository;

use App\Bot\Entity\Game;
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
}
