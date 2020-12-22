<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Answer;

use App\Core\Answer\AnswerRepositoryInterface;
use App\Core\Answer\Specification\SpecificationInterface;
use App\Core\Entity\Answer;
use App\Core\Node\Entity\Node;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class AnswerRepository implements AnswerRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function satisfyBy(SpecificationInterface $specification): array
    {
        return $this->applySpecification($specification)->getResult();
    }

    public function satisfyOneBy(SpecificationInterface $specification): ?Answer
    {
        return $this->applySpecification($specification)->getOneOrNullResult();
    }

    private function applySpecification(SpecificationInterface $specification): Query
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('a')
            ->from(Answer::class, 'a');
        $specification->match($qb,'a');
        return $qb->getQuery();
    }

    /**
     * @param Answer $answer
     */
    public function save(Answer $answer): void
    {
        $this->entityManager->persist($answer);
        $this->entityManager->flush();
    }

    public function remove(Answer $answer): void
    {
        $this->entityManager->remove($answer);
        $this->entityManager->flush();
    }
}
