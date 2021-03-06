<?php

namespace App\Core\Admin\Game;

use App\Core\Answer\AnswerRepositoryInterface;
use App\Core\Answer\Specification\QuestionIdSpecification;
use App\Core\Game\Entity\Game;
use App\Core\Game\GameRepositoryInterface;
use App\Core\Question\QuestionRepositoryInterface;
use App\Core\Question\Specification\GameIdSpecification;

class GameService
{

    /**
     * @var GameRepositoryInterface
     */
    private $gameRepository;
    /**
     * @var QuestionRepositoryInterface
     */
    private $questionRepository;
    /**
     * @var AnswerRepositoryInterface
     */
    private $answerRepository;

    /**
     * GameService constructor.
     * @param GameRepositoryInterface $gameRepository
     * @param QuestionRepositoryInterface $questionRepository
     * @param AnswerRepositoryInterface $answerRepository
     */
    public function __construct(GameRepositoryInterface $gameRepository, QuestionRepositoryInterface $questionRepository, AnswerRepositoryInterface $answerRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
    }

    public function getGraph(Game $game): array
    {
        $questions = $this->questionRepository->satisfyBy(new GameIdSpecification($game->getId()));
        $result = [];
        foreach ($questions as $question) {
            $answers = $this->answerRepository->satisfyBy(new QuestionIdSpecification($question->getId()));
            $data = $question->toArray($answers);
            $result[] = $data;
        }
        return $result;
    }
}
