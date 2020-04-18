<?php

namespace App\Core\Admin\Game;

use App\Core\Answer\AnswerRepositoryInterface;
use App\Core\Entity\Answer;
use App\Core\Question\Entity\Question;
use App\Core\Game\GameRepositoryInterface;
use App\Core\Question\QuestionRepositoryInterface;
use App\Editor\DTO\Node;
use DomainException;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

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
     * @var GameRepositoryInterface
     */
    private $gameRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * QuestionService constructor.
     * @param QuestionRepositoryInterface $questionRepository
     * @param SerializerInterface $serializer
     * @param AnswerRepositoryInterface $answerRepository
     * @param GameRepositoryInterface $gameRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        QuestionRepositoryInterface $questionRepository,
        SerializerInterface $serializer,
        AnswerRepositoryInterface $answerRepository,
        GameRepositoryInterface $gameRepository,
        LoggerInterface $logger
    ) {
        $this->questionRepository = $questionRepository;
        $this->serializer = $serializer;
        $this->answerRepository = $answerRepository;
        $this->gameRepository = $gameRepository;
        $this->logger = $logger;
    }

    public function updateQuestion(int $gameId, string $json): Question
    {
        /** @var Node $node */
        $node = $this->serializer->deserialize($json, Node::class, 'json');

        if ($node->getId()) {
            $question = $this->questionRepository->findQuestion($node->getId());
            if (!$question) {
                throw new \OutOfRangeException('Question not found');
            }
        } else {
            $game = $this->gameRepository->findById($gameId);
            $question = new Question($game, false);
        }

        $question
            ->setLocationX($node->getPosition()->getX())
            ->setLocationY($node->getPosition()->getY())
            ->setText($node->getText())
        ;

        $this->questionRepository->save($question);

        $answers = $this->answerRepository->findByQuestion($question);
        $nodeAnswers = $node->getAnswers();
        foreach ($nodeAnswers as $nodeAnswer) {
            $answer = null;
            if ($nodeAnswer->getId()) {
                foreach ($answers as $answer) {
                    if ($nodeAnswer->getId() === $answer->getId()) {

                        $answer->setText($nodeAnswer->getText());
                        break;
                    }
                }
                if (!$answer) {
                    throw new \OutOfBoundsException('Answer not found');
                }
            } else {
                $answer = new Answer(
                    $question,
                    $nodeAnswer->getText(),
                    null
                );
            }
            $nextQuestion = null;
            if ($nodeAnswer->getNextQuestionId()) {
                $nextQuestion = $this->questionRepository->findQuestion($nodeAnswer->getNextQuestionId());
            }
            $answer->setNextQuestion($nextQuestion);
            $this->answerRepository->save($answer);
        }
        $answers = $this->answerRepository->findByQuestion($question);
        $this->removeAnswers($nodeAnswers, $answers);

        return $question;
    }

    public function deleteQuestion(int $gameId, int $questionId): void
    {
        $question = $this->questionRepository->findQuestion($questionId);
        if (!$question) {
            $this->logger->error('Question already removed', [
                'questionId' => $questionId
            ]);
            return;
        }
        $links = $this->answerRepository->findByNextQuestion($question);
        foreach ($links as $answer) {
            $answer->setNextQuestion(null);
            $this->answerRepository->save($answer);
        }
        $answers = $this->answerRepository->findByQuestion($question);
        foreach ($answers as $answer) {
            $this->answerRepository->remove($answer);
        }
        $this->questionRepository->remove($question);
    }

    public function serialize(Question $question): string
    {
        return $this->serializer->serialize($question, 'json');
    }

    /**
     * @param \App\Editor\DTO\Answer[] $nodeAnswers
     * @param Answer[] $answers
     */
    private function removeAnswers(array $nodeAnswers, array $answers)
    {
        $deletedAnswers = array_filter($answers, function (Answer $answer) use ($nodeAnswers) {
            foreach ($nodeAnswers as $nodeAnswer) {
                if ($nodeAnswer->getId() === null || $nodeAnswer->getId() === $answer->getId()) {
                    return false;
                }
            }
            return true;
        });
        foreach ($deletedAnswers as $answer) {
            $this->answerRepository->remove($answer);
        }
    }
}
