<?php

namespace App\Core\Question;

use App\Core\Question\Entity\Question;
use App\Core\Question\Specification\SpecificationInterface;
use Doctrine\ORM\Query;

interface QuestionRepositoryInterface
{
    public function getPaginatorQuery(int $gameId): Query;

    public function save(Question $question): void;

    /**
     * @param SpecificationInterface $specification
     * @return Question[]
     */
    public function satisfyBy(SpecificationInterface $specification): array;

    public function satisfyOneBy(SpecificationInterface $specification): ?Question;

    public function remove(Question $question): void;
}
