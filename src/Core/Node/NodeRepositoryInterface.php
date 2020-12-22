<?php

namespace App\Core\Node;

use App\Core\Node\Entity\Node;
use App\Core\Node\Specification\SpecificationInterface;
use Doctrine\ORM\Query;

interface NodeRepositoryInterface
{
    public function getPaginatorQuery(int $gameId): Query;

    public function save(Node $question): void;

    /**
     * @param SpecificationInterface $specification
     * @return Node[]
     */
    public function satisfyBy(SpecificationInterface $specification): array;

    public function satisfyOneBy(SpecificationInterface $specification): ?Node;

    public function remove(Node $question): void;
}
