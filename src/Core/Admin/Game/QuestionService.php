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
use App\Core\Question\Specification\IdSpecification;
use App\Editor\DTO\Node;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;

class QuestionService
{
    private QuestionRepositoryInterface $questionRepository;
    private AnswerRepositoryInterface $answerRepository;
    private SerializerInterface $serializer;
    private GameRepositoryInterface $gameRepository;
    private LoggerInterface $logger;

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

    public function updateQuestion(Game $game, string $json): Node
    {
        /** @var Node $node */
        $node = $this->serializer->deserialize($json, Node::class, 'json');

        if ($node->getId()) {
            $question = $this->questionRepository->satisfyOneBy(new IdSpecification($node->getId()));
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
        $node->setId($question->getId());

        $answers = $this->answerRepository->satisfyBy(new QuestionIdSpecification($question->getId()));
        $nodeAnswers = $node->getAnswers();
        foreach ($nodeAnswers as $key => $nodeAnswer) {
            $answer = null;
            $isNewAnswer = false;
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
                $isNewAnswer = true;
            }
            $nextQuestion = null;
            if ($nodeAnswer->getNextQuestionId()) {
                $nextQuestion = $this->questionRepository->satisfyOneBy(
                    new IdSpecification($nodeAnswer->getNextQuestionId())
                );
            }
            $answer->setNextQuestion($nextQuestion);
            $this->answerRepository->save($answer);
            if ($isNewAnswer) {
                $nodeAnswers[$key]->setId($answer->getId());
            }
        }
        $answers = $this->answerRepository->satisfyBy(new QuestionIdSpecification($question->getId()));
        $this->removeAnswers($nodeAnswers, $answers);

        return $node;
    }

    public function deleteQuestion(Game $game, int $questionId): void
    {
        $question = $this->questionRepository->satisfyOneBy(new IdSpecification($questionId));
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

    private function toNodeDto(Question $question)
    {

    }
}
