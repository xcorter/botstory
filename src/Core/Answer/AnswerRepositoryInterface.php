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
}