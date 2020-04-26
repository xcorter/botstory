<?php

namespace App\Core\Question\Specification;

use Doctrine\ORM\QueryBuilder;

class GameIdSpecification implements SpecificationInterface
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function match(QueryBuilder $queryBuilder, string $alias)
    {
        $queryBuilder
            ->where("$alias.game = :id")
            ->setParameter('id', $this->id)
        ;
    }
}