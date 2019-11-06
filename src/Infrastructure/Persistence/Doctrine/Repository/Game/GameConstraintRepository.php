<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Game;

use App\Core\Game\Entity\Game;
use App\Core\Game\Entity\GameConstraint;
use App\Core\Game\GameConstraintRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class GameConstraintRepository implements GameConstraintRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * GameConstraintRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Game $game
     * @return array
     */
    public function findConstraints(Game $game): array
    {
        return $this->entityManager->getRepository(GameConstraint::class)->findBy([
            'game' => $game
        ]);
    }
}
