<?php

namespace App\Core\Answer;

use App\Core\Answer\Specification\SpecificationInterface;
use App\Core\Entity\Answer;

interface AnswerRepositoryInterface
{
    /**
     * @param SpecificationInterface $specification
     * @return Answer[]
     */
    public function satisfyBy(SpecificationInterface $specification): array;

    public function satisfyOneBy(SpecificationInterface $specification): ?Answer;

    public function save(Answer $answer): void;

    public function remove(Answer $answer): void;
}
