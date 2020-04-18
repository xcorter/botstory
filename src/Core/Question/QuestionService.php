<?php

namespace App\Core\Question;

use App\Core\Game\Entity\Game;
use App\Core\Question\Entity\Question;

class QuestionService
{
    /**
     * @var QuestionRepositoryInterface
     */
    private $questionRepository;

    /**
     * QuestionService constructor.
     * @param QuestionRepositoryInterface $questionRepository
     */
    public function __construct(QuestionRepositoryInterface $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function createStartNode(Game $game): void
    {
        $question = new Question($game, true);
        $question
            ->setText('It is starts here...')
            ->setLocationX(100)
            ->setLocationY(100)
        ;
        $this->questionRepository->save($question);
    }
}