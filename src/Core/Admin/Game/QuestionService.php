<?php

namespace App\Core\Admin\Game;

use App\Core\Question\QuestionRepositoryInterface;
use Psr\Log\LoggerInterface;

class QuestionService
{
    /**
     * @var QuestionRepositoryInterface
     */
    private $questionRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * QuestionService constructor.
     * @param QuestionRepositoryInterface $questionRepository
     * @param LoggerInterface $logger
     */
    public function __construct(QuestionRepositoryInterface $questionRepository, LoggerInterface $logger)
    {
        $this->questionRepository = $questionRepository;
        $this->logger = $logger;
    }

    public function updateQuestion(int $questionId, array $fields): void
    {
        $question = $this->questionRepository->findQuestion($questionId);
        if (!$question) {
            throw new \OutOfRangeException('Question not found');
        }
        // TODO проверять типы значений  BOT-51
        // @link https://trello.com/c/aHR9cSpb
        foreach ($fields as $field => $value) {
            $method = $this->getSetterByField($field);
            if (method_exists($question, $method)) {
                $question->$method($value);
            } else {
                $this->logger->error('Method not found', [
                    'method' => $method,
                    'questionId' => $questionId
                ]);
            }
        }
        $this->questionRepository->save($question);
    }

    private function getSetterByField(string $field): string
    {
        return 'set' . ucfirst($field);
    }
}