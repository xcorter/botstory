<?php

namespace App\Core\Question;

use App\Core\Question\Entity\Question;
use Doctrine\ORM\Query;

interface QuestionRepositoryInterface
{
    /**
     * @param int $gameId
     * @return Question[]
     */
    public function findAllQuestionsByGameId(int $gameId): array;

    public function getPaginatorQuery(int $gameId): Query;

    public function save(Question $question): void;

    public function findQuestion(int $id): ?Question;

    public function getStartQuestion(int $gameId): Question;

    public function remove(Question $question): void;
}
