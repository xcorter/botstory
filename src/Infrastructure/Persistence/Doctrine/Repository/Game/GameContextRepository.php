<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Game;

use App\Core\Entity\User;
use App\Core\Game\Entity\Game;
use App\Core\Game\Entity\GameContext;
use App\Core\Game\GameContextRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class GameContextRepository implements GameContextRepositoryInterface
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

    public function findGameContext(User $user, Game $game): ?GameContext
    {
        return $this->entityManager->getRepository(GameContext::class)->findOneBy([
            'user' => $user,
            'game' => $game,
        ]);
    }

    public function save(GameContext $gameContext): void
    {
        $this->entityManager->persist($gameContext);
        $this->entityManager->flush();
    }

    public function delete(GameContext $gameContext): void
    {
        $this->entityManager->remove($gameContext);
        $this->entityManager->flush();
    }
}
