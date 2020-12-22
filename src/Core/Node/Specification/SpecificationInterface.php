<?php

namespace App\Core\Node\Specification;

use Doctrine\ORM\QueryBuilder;

interface SpecificationInterface
{

    public function match(QueryBuilder $queryBuilder, string $alias);
}