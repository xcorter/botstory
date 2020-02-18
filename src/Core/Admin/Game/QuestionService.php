<?php

namespace App\Core\Admin\Game;

use App\Core\Answer\AnswerRepositoryInterface;
use App\Core\Entity\Question;
use App\Core\Question\QuestionRepositoryInterface;
use App\Editor\DTO\Node;
use JMS\Serializer\SerializerInterface;

class QuestionService
{
    /**
     * @var QuestionRepositoryInterface
     */
    private $questionRepository;
    /**
     * @var AnswerRepositoryInterface
     */
    private $answerRepository;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * QuestionService constructor.
     * @param QuestionRepositoryInterface $questionRepository
     * @param SerializerInterface $serializer
     * @param AnswerRepositoryInterface $answerRepository
     */
    public function __construct(
        QuestionRepositoryInterface $questionRepository,
        SerializerInterface $serializer,
        AnswerRepositoryInterface $answerRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->serializer = $serializer;
        $this->answerRepository = $answerRepository;
    }

    public function updateQuestion(int $questionId, string $json): void
    {
        $question = $this->questionRepository->findQuestion($questionId);
        if (!$question) {
            throw new \OutOfRangeException('Question not found');
        }
        /** @var Node $node */
        $node = $this->serializer->deserialize($json, Node::class, 'json');

        $question
            ->setLocationX($node->getPosition()->getX())
            ->setLocationY($node->getPosition()->getY())
            ->setText($node->getText())
        ;

        $this->questionRepository->save($question);

        $answers = $this->answerRepository->findByQuestion($question);
        foreach ($answers as $answer) {
            $nodeAnswers = $node->getAnswers();
            foreach ($nodeAnswers as $nodeAnswer) {
                if ($nodeAnswer->getId() === $answer->getId()) {
                    $answer->setText($nodeAnswer->getText());
                    $this->answerRepository->save($answer);
                }
            }
        }
    }
}