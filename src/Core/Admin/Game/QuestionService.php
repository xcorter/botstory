<?php

namespace App\Core\Admin\Game;

use App\Core\Answer\AnswerRepositoryInterface;
use App\Core\Answer\Specification\NextQuestionSpecification;
use App\Core\Answer\Specification\QuestionIdSpecification;
use App\Core\Entity\Answer;
use App\Core\Game\Entity\Game;
use App\Core\Question\Entity\Question;
use App\Core\Game\GameRepositoryInterface;
use App\Core\Question\QuestionRepositoryInterface;
use App\Core\Question\Specification\FindByIdSpecification;
use App\Editor\DTO\Node;
use DomainException;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;

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

    public function updateQuestion(Game $game, string $json): Question
    {
        /** @var Node $node */
        $node = $this->serializer->deserialize($json, Node::class, 'json');

        if ($node->getId()) {
            $question = $this->questionRepository->satisfyOneBy(new FindByIdSpecification($node->getId()));
            if (!$question) {
                throw new \OutOfRangeException('Question not found');
            }
            if (!$question->belongsTo($game)) {
                throw new \Exception('User does not have grants');
            }
        } else {
            $question = new Question($game, false);
        }

        $question
            ->setLocationX($node->getPosition()->getX())
            ->setLocationY($node->getPosition()->getY())
            ->setText($node->getText())
        ;

        $this->questionRepository->save($question);

        $answers = $this->answerRepository->satisfyBy(new QuestionIdSpecification($question->getId()));
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
                $nextQuestion = $this->questionRepository->satisfyOneBy(
                    new FindByIdSpecification($nodeAnswer->getNextQuestionId())
                );
            }
            $answer->setNextQuestion($nextQuestion);
            $this->answerRepository->save($answer);
        }
        $answers = $this->answerRepository->satisfyBy(new QuestionIdSpecification($question->getId()));
        $this->removeAnswers($nodeAnswers, $answers);

        return $question;
    }

    public function deleteQuestion(Game $game, int $questionId): void
    {
        $question = $this->questionRepository->satisfyOneBy(new FindByIdSpecification($questionId));
        if (!$question) {
            $this->logger->error('Question already removed', [
                'questionId' => $questionId
            ]);
            return;
        }
        if (!$question->belongsTo($game)) {
            $errorMessage = 'Question does not belongs to game';
            $this->logger->error($errorMessage, [
                'questionId' => $questionId,
                'gameId' => $game->getId(),
            ]);
            throw new RuntimeException($errorMessage);
        }
        $links = $this->answerRepository->satisfyBy(new NextQuestionSpecification($question->getId()));
        foreach ($links as $answer) {
            $answer->setNextQuestion(null);
            $this->answerRepository->save($answer);
        }
        $answers = $this->answerRepository->satisfyBy(new QuestionIdSpecification($question->getId()));
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
