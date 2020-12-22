<?php

namespace App\Core\Mode;

use App\Bot\Telegram\Transform\ResponseConverter;
use App\Bot\Telegram\Util\Helper;
use App\Core\Answer\AnswerRepositoryInterface;
use App\Core\Answer\Specification\QuestionIdSpecification;
use App\Core\Entity\Answer;
use App\Core\Node\Entity\Node;
use App\Core\Entity\Player;
use App\Core\Game\GameContextRepositoryInterface;
use App\Core\Game\GameContextService;
use App\Core\Game\GameRepositoryInterface;
use App\Core\Interaction\ActionApplier;
use App\Core\Interaction\ConstraintsFactory;
use App\Core\Interaction\InteractionService;
use App\Core\Node\NodeRepositoryInterface;
use App\Core\Node\Specification\IdSpecification;
use App\Core\Node\Specification\StartQuestionSpecification;
use SimpleTelegramBotClient\Dto\Type\Message;
use SimpleTelegramBotClient\TelegramService;

class RunGameMode implements ModeInterface
{
    /**
     * @var GameRepositoryInterface
     */
    private $gameRepository;
    /**
     * @var NodeRepositoryInterface
     */
    private $questionRepository;
    /**
     * @var InteractionService
     */
    private $interactionService;
    /**
     * @var ResponseConverter
     */
    private $responseConverter;
    /**
     * @var TelegramService
     */
    private $telegramService;
    /**
     * @var AnswerRepositoryInterface
     */
    private $answerRepository;
    /**
     * @var ActionApplier
     */
    private $actionApplier;
    /**
     * @var ConstraintsFactory
     */
    private $constraintsFactory;
    /**
     * @var GameContextRepositoryInterface
     */
    private $gameContextRepository;
    /**
     * @var GameOverMode
     */
    private $gameOverMode;
    /**
     * @var GameContextService
     */
    private $gameContextService;

    /**
     * RunGameMode constructor.
     * @param GameRepositoryInterface $gameRepository
     * @param NodeRepositoryInterface $questionRepository
     * @param InteractionService $interactionService
     * @param ResponseConverter $responseConverter
     * @param TelegramService $telegramService
     * @param AnswerRepositoryInterface $answerRepository
     * @param ActionApplier $actionApplier
     * @param ConstraintsFactory $constraintsFactory
     * @param GameContextRepositoryInterface $gameContextRepository
     * @param GameOverMode $gameOverMode
     * @param GameContextService $gameContextService
     */
    public function __construct(GameRepositoryInterface $gameRepository, NodeRepositoryInterface $questionRepository, InteractionService $interactionService, ResponseConverter $responseConverter, TelegramService $telegramService, AnswerRepositoryInterface $answerRepository, ActionApplier $actionApplier, ConstraintsFactory $constraintsFactory, GameContextRepositoryInterface $gameContextRepository, GameOverMode $gameOverMode, GameContextService $gameContextService)
    {
        $this->gameRepository = $gameRepository;
        $this->questionRepository = $questionRepository;
        $this->interactionService = $interactionService;
        $this->responseConverter = $responseConverter;
        $this->telegramService = $telegramService;
        $this->answerRepository = $answerRepository;
        $this->actionApplier = $actionApplier;
        $this->constraintsFactory = $constraintsFactory;
        $this->gameContextRepository = $gameContextRepository;
        $this->gameOverMode = $gameOverMode;
        $this->gameContextService = $gameContextService;
    }

    public function run(Player $user, Message $message): void
    {
        $currentQuestionId = $user->getContext()->getCurrentQuestion();
        $gameId = $user->getContext()->getCurrentGame();
        if (!$gameId) {
            return;
        }

        $game = $this->gameRepository->findById($gameId);
        if (!$currentQuestionId) {
            // Начало игры
            $user->runGame($gameId);
            $node = $this->questionRepository->satisfyOneBy(new StartQuestionSpecification($gameId));
        } else {
            $currentQuestion = $this->questionRepository->satisfyOneBy(new IdSpecification($currentQuestionId));
            if (!$currentQuestion) {
                throw new \RuntimeException('Node not found');
            }
            try {
                $answer = $this->getAnswer($message, $currentQuestion);
            } catch (\LogicException $e) {
                // END OF GAME
                $user->resetContext();
                $this->gameContextService->removeGameContext($user, $game);
                return;
            }

            $node = $answer->getNextNode();
            if ($node->isFinish()) {
                $this->sendMessage($message, $node);
                $user->resetContext();
                $this->gameContextService->removeGameContext($user, $game);
                return;
            }
        }
        $constraint = $this->constraintsFactory->createConstraint($game);
        $gameContext = $this->gameContextRepository->findGameContext($user, $game);
        if (!$constraint->isSatisfiedBy($gameContext)) {
            $user->resetContext();
            $this->gameContextService->removeGameContext($user, $game);
            return;
        }

        if (!$node) {
            return;
        }
        if ($node->getType() === Node::TYPE_TEXT) {
            $user->setCurrentQuestion($node->getId());
        } elseif ($node->getType() === Node::TYPE_ACTION) {
            $this->actionApplier->apply($user, $node->getActions());
        }

        $this->sendMessage($message, $node);
    }

    private function sendMessage(Message $message, Node $question)
    {
        $chatId = (string) $message->getChat()->getId();
        $interactionResponse = $this->interactionService->showQuestion($chatId, $question);
        $sendMessage = $this->responseConverter->convertToTelegramMessage($interactionResponse);
        $this->telegramService->sendMessage($sendMessage);
    }

    /**
     * @param Message $message
     * @param Node $question
     * @return Answer
     */
    private function getAnswer(Message $message, Node $question): Answer
    {
        $answers = $this->answerRepository->satisfyBy(new QuestionIdSpecification($question->getId()));
        $normalizedAnswer = Helper::trim($message->getText());
        foreach ($answers as $answer) {
            if ($answer->getText() === $normalizedAnswer) {
                return $answer;
            }
        }
        throw new \LogicException('Answer not found');
    }
}
