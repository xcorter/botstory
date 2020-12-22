<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Node;

use App\Core\Node\Entity\Node;
use App\Core\Node\NodeRepositoryInterface;
use App\Core\Node\Specification\SpecificationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

/**
 * @method createQueryBuilder(string $string)
 */
class NodeRepository implements NodeRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getPaginatorQuery(int $gameId): Query
    {
        return $this->entityManager->createQueryBuilder()
            ->select('q.id', 'q.text')
            ->from(Node::class, 'q')
            ->andWhere('q.game = :gameId')
            ->setParameter('gameId', $gameId)
            ->getQuery();
    }

    public function save(Node $question): void
    {
        $this->entityManager->persist($question);
        $this->entityManager->flush();
    }

    /**
     * @param SpecificationInterface $specification
     * @return Node[]
     */
    public function satisfyBy(SpecificationInterface $specification): array
    {
        return $this->applySpecification($specification)->getResult();
    }

    public function satisfyOneBy(SpecificationInterface $specification): ?Node
    {
        return $this->applySpecification($specification)->getOneOrNullResult();
    }

    private function applySpecification(SpecificationInterface $specification): Query
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('q')
            ->from(Node::class, 'q');
        $specification->match($qb,'q');
        return $qb->getQuery();
    }

    public function remove(Node $question): void
    {
        $this->entityManager->remove($question);
        $this->entityManager->flush();
    }
}
