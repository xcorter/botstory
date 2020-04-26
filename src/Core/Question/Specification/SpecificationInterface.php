<?php

namespace App\Core\Question\Specification;

use Doctrine\ORM\QueryBuilder;

interface SpecificationInterface
{

    public function match(QueryBuilder $queryBuilder, string $alias);
}