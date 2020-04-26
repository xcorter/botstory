<?php

namespace App\Core\Answer\Specification;

use Doctrine\ORM\QueryBuilder;

class IdSpecification implements SpecificationInterface
{

    private int $id;

    /**
     * IdSpecification constructor.
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