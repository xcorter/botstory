<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Game;

use App\Core\Game\CharacteristicRepositoryInterface;
use App\Core\Game\Entity\Characteristic;
use App\Core\Game\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;

class CharacteristicRepository implements CharacteristicRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CharacteristicRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Game $game
     * @return Characteristic[]
     */
    public function findByGame(Game $game): array
    {
        return $this->entityManager->getRepository(Characteristic::class)->findBy([
            'game' => $game
        ]);
    }
}
