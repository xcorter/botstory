<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Script;

use App\Core\Entity\Game;
use App\Core\Entity\Script;
use App\Core\Script\ScriptRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

/**
 * @method createQueryBuilder(string $string)
 */
class ScriptRepository implements ScriptRepositoryInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ScriptRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAllScriptsByGameId(int $gameId): array
    {
        return $this->entityManager->getRepository(Script::class)->findBy(
            [
                'game' => $gameId
            ],
            [
                'step' => 'ASC'
            ]
        );
    }

    public function getPaginatorQuery(int $gameId): Query
    {
        return $this->entityManager->createQueryBuilder()
            ->select('q.id', 'q.text')
            ->from(Script::class, 'q')
            ->andWhere('q.game = :gameId')
            ->setParameter('gameId', $gameId)
            ->orderBy('q.step', 'ASC')
            ->getQuery();
    }

    public function save(Script $script): void
    {
        $this->entityManager->persist($script);
        $this->entityManager->flush();
    }

    public function getScript(Game $game, int $step): Script
    {
        $script = $this->findScript($game, $step);
        if (!$script) {
            throw new \RangeException('Script not found');
        }
        return $script;
    }

    public function findNextScript(Game $game, Script $currentScript): ?Script
    {
        $step = $currentScript->getStep() + 1;
        return $this->findScript($game, $step);
    }

    private function findScript(Game $game, int $step): ?Script
    {
        return $this->entityManager->getRepository(Script::class)
            ->findOneBy([
                'game' => $game,
                'step' => $step,
            ]);
    }
}
