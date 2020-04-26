<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Question;

use App\Core\Question\Entity\Question;
use App\Core\Question\QuestionRepositoryInterface;
use App\Core\Question\Specification\SpecificationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

/**
 * @method createQueryBuilder(string $string)
 */
class QuestionRepository implements QuestionRepositoryInterface
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
            ->from(Question::class, 'q')
            ->andWhere('q.game = :gameId')
            ->setParameter('gameId', $gameId)
            ->getQuery();
    }

    public function save(Question $question): void
    {
        $this->entityManager->persist($question);
        $this->entityManager->flush();
    }

    /**
     * @param SpecificationInterface $specification
     * @return Question[]
     */
    public function satisfyBy(SpecificationInterface $specification): array
    {
        return $this->applySpecification($specification)->getResult();
    }

    public function satisfyOneBy(SpecificationInterface $specification): ?Question
    {
        return $this->applySpecification($specification)->getOneOrNullResult();
    }

    private function applySpecification(SpecificationInterface $specification): Query
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('q')
            ->from(Question::class, 'q');
        $specification->match($qb,'q');
        return $qb->getQuery();
    }

    public function remove(Question $question): void
    {
        $this->entityManager->remove($question);
        $this->entityManager->flush();
    }
}
