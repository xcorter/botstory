<?php

namespace App\Core\Repository;


use App\Core\Entity\Script;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method createQueryBuilder(string $string)
 */
class ScriptRepository
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

    public function getPaginatorQuery(int $gameId): \Doctrine\ORM\Query
    {
       return   $this->entityManager->createQueryBuilder()
            ->select('id', 'game', 'text')
            ->from('script', 'q')
            ->andWhere('q.game = :gameId')
            ->setParameter('gameId', $gameId)
            ->orderBy('q.step', 'ASC')
            ->getQuery();
    }
}
