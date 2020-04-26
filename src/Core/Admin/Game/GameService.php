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
            $data = $question->toArray();
            $answers = $this->answerRepository->satisfyBy(new QuestionIdSpecification($question->getId()));
            foreach ($answers as $answer) {
                $nextQuestionId =
                    $answer->getNextQuestion() ? $answer->getNextQuestion()->getId() : null;
                $data['answers'][] = [
                    'next_question_id' => $nextQuestionId,
                    'id' => $answer->getId(),
                    'text' => $answer->getText()
                ];
            }
            $result[] = $data;
        }
        return $result;
    }
}
