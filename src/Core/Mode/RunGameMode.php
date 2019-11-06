<?php

namespace App\Core\Mode;

use App\Bot\Telegram\Transform\ResponseConverter;
use App\Bot\Telegram\Util\Helper;
use App\Core\Answer\AnswerRepository;
use App\Core\Entity\Answer;
use App\Core\Entity\Script;
use App\Core\Entity\User;
use App\Core\Game\GameContextRepositoryInterface;
use App\Core\Game\GameContextService;
use App\Core\Game\GameRepositoryInterface;
use App\Core\Interaction\ActionApplier;
use App\Core\Interaction\ConstraintsFactory;
use App\Core\Interaction\InteractionService;
use App\Core\Script\ScriptRepositoryInterface;
use SimpleTelegramBotClient\Dto\Type\Message;
use SimpleTelegramBotClient\TelegramService;

class RunGameMode implements ModeInterface
{
    /**
     * @var GameRepositoryInterface
     */
    private $gameRepository;
    /**
     * @var ScriptRepositoryInterface
     */
    private $scriptRepository;
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
     * @var AnswerRepository
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
     * @param ScriptRepositoryInterface $scriptRepository
     * @param InteractionService $interactionService
     * @param ResponseConverter $responseConverter
     * @param TelegramService $telegramService
     * @param AnswerRepository $answerRepository
     * @param ActionApplier $actionApplier
     * @param ConstraintsFactory $constraintsFactory
     * @param GameContextRepositoryInterface $gameContextRepository
     * @param GameOverMode $gameOverMode
     * @param GameContextService $gameContextService
     */
    public function __construct(GameRepositoryInterface $gameRepository, ScriptRepositoryInterface $scriptRepository, InteractionService $interactionService, ResponseConverter $responseConverter, TelegramService $telegramService, AnswerRepository $answerRepository, ActionApplier $actionApplier, ConstraintsFactory $constraintsFactory, GameContextRepositoryInterface $gameContextRepository, GameOverMode $gameOverMode, GameContextService $gameContextService)
    {
        $this->gameRepository = $gameRepository;
        $this->scriptRepository = $scriptRepository;
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

    public function run(User $user, Message $message): void
    {
        $currentScriptId = $user->getContext()->getCurrentScript();
        $gameId = $user->getContext()->getCurrentGame();

        $game = $this->gameRepository->findById($gameId);
        if (!$currentScriptId) {
            // Начало игры
            $user->runGame($gameId);
            $script = $this->scriptRepository->getScriptByStep($game, ScriptRepositoryInterface::FIRST_STEP);
        } else {
            $currentScript = $this->scriptRepository->findScript($currentScriptId);
            if (!$currentScript) {
                throw new \RuntimeException('Script not found');
            }
            $answer = $this->getAnswer($message, $currentScript);
            if ($answer) {
                if ($answer->hasAction()) {
                    $this->actionApplier->apply($user, $answer->getAction());
                }
                $script = $this->scriptRepository->findNextScript($game, $currentScript);
            } else {
                $script = $currentScript;
            }
            $constraint = $this->constraintsFactory->createConstraint($game);
            $gameContext = $this->gameContextRepository->findGameContext($user, $game);
            if (!$constraint->isSatisfiedBy($gameContext)) {
                $user->resetContext();
                $this->gameContextService->removeGameContext($user, $game);
                return;
            }
        }
        if (!$script) {
            return;
        }

        $user->getContext()->setCurrentScript($script->getId());

        $chatId = $message->getChat()->getId();
        $interactionResponse = $this->interactionService->showScript($chatId, $script);
        $sendMessage = $this->responseConverter->convertToTelegramMessage($interactionResponse);
        $this->telegramService->sendMessage($sendMessage);
    }

    /**
     * @param Message $message
     * @param Script $script
     * @return Answer|null
     */
    private function getAnswer(Message $message, Script $script): ?Answer
    {
        $answers = $this->answerRepository->findByScript($script);
        $normalizedAnswer = Helper::trim($message->getText());
        foreach ($answers as $answer) {
            if ($answer->getText() === $normalizedAnswer) {
                return $answer;
            }
        }
        if ($normalizedAnswer === '...') {
            // TODO ploho ploho ploho
            return new Answer($script, '...', null);
        }
        return null;
    }
}
