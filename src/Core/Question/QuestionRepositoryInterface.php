<?php

namespace App\Core\Question;

use App\Core\Game\Entity\Game;
use App\Core\Entity\Question;
use Doctrine\ORM\Query;

interface QuestionRepositoryInterface
{

    public function findAllQuestionsByGameId(int $gameId): array;

    public function getPaginatorQuery(int $gameId): Query;

    public function save(Question $question): void;

    public function findQuestion(int $id): ?Question;

    public function getStartQuestion(int $gameId): Question;
}
