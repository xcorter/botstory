<?php

namespace App\Core\Answer\Specification;

use Doctrine\ORM\QueryBuilder;

interface SpecificationInterface
{
    public function match(QueryBuilder $queryBuilder, string $alias);
}