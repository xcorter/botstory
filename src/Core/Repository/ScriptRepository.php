<?php

namespace App\Core\Repository;


use App\Core\Entity\Script;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;

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

    /*public function saveScript(int $gameId, Request $request)
    {
        $script = new Script($request->get('game'), $request->get('step'), $request->get('text'));
        $script->setGame($request->get($gameId));
        $script->setGame($request->get('game'));
        $script->setGame($request->get('game'));


         $this->entityManager->persist($script);
        $this->entityManager->flush($script);
        return $this->entityManager;
    }*/
}
