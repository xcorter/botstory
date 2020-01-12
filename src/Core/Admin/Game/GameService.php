<?php

namespace App\Core\Admin\Game;

use App\Core\Answer\AnswerRepositoryInterface;
use App\Core\Game\GameRepositoryInterface;
use App\Core\Question\QuestionRepositoryInterface;

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

    public function getGraph(int $gameId): array
    {
        $questions = $this->questionRepository->findAllQuestionsByGameId($gameId);
        $result = [];
        foreach ($questions as $question) {
            $data = [
                'id' => $question->getId(),
                'text' => $question->getText(),
                'position' => [
                    'x' => $question->getLocationX(),
                    'y' => $question->getLocationY(),
                ],
                'answers' => []
            ];
            $answers = $this->answerRepository->findByQuestion($question);
            foreach ($answers as $answer) {
                $data['answers'][] = [
                    'id' => $answer->getNextQuestion()->getId(),
                    'text' => $answer->getText()
                ];
            }
            $result[] = $data;
        }
        return $result;
    }
}