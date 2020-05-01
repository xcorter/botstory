<?php

namespace App\Core\Question\Specification;

use Doctrine\ORM\QueryBuilder;

class IdSpecification implements SpecificationInterface
{
    private int $id;

    /**
     * FindByIdSpecification constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function match(QueryBuilder $queryBuilder, string $alias)
    {
        $queryBuilder
            ->where("$alias.id = :id")
            ->setParameter('id', $this->id)
        ;
    }
}