<?php

namespace App\Core\Admin\Game;

use App\Core\Question\QuestionRepositoryInterface;

class QuestionService
{
    /**
     * @var QuestionRepositoryInterface
     */
    private $questionRepository;
    /**
     * @var UpdateEntityHelper
     */
    private $updateEntityHelper;

    /**
     * QuestionService constructor.
     * @param QuestionRepositoryInterface $questionRepository
     * @param UpdateEntityHelper $updateEntityHelper
     */
    public function __construct(QuestionRepositoryInterface $questionRepository, UpdateEntityHelper $updateEntityHelper)
    {
        $this->questionRepository = $questionRepository;
        $this->updateEntityHelper = $updateEntityHelper;
    }

    public function updateQuestion(int $questionId, array $fields): void
    {
        $question = $this->questionRepository->findQuestion($questionId);
        if (!$question) {
            throw new \OutOfRangeException('Question not found');
        }
        $this->updateEntityHelper->setToEntity($question, $fields);
        $this->questionRepository->save($question);
    }
}