<?php

namespace App\Core\Answer;

use App\Core\Entity\Answer;
use App\Core\Entity\Question;

interface AnswerRepositoryInterface
{
    /**
     * @param Question $question
     * @return Answer[]
     */
    public function findByQuestion(Question $question): array;

    /**
     * @param int $id
     * @return Answer|null
     */
    public function find(int $id): ?Answer;

    /**
     * @param Answer $answer
     */
    public function save(Answer $answer): void;
}