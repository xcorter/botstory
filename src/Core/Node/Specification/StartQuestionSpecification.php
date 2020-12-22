<?php

namespace App\Core\Node\Specification;

use Doctrine\ORM\QueryBuilder;

class StartQuestionSpecification implements SpecificationInterface
{

    private int $id;

    /**
     * StartQuestionSpecification constructor.
     * @param int $questionId
     */
    public function __construct(int $questionId)
    {
        $this->id = $questionId;
    }

    public function match(QueryBuilder $queryBuilder, string $alias)
    {
        $queryBuilder
            ->where("$alias.id = :id")
            ->andWhere("$alias.isStart = :val")
            ->setParameter('id', $this->id)
            ->setParameter('val', true)
        ;
    }
}