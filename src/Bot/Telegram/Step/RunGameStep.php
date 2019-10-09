<?php

namespace App\Bot\Telegram\Step;

use App\Bot\Telegram\Transform\ResponseConverter;
use App\Bot\Telegram\Util\Helper;
use App\Core\Answer\AnswerRepository;
use App\Core\Entity\Answer;
use App\Core\Entity\Script;
use App\Core\Entity\User;
use App\Core\Game\GameRepositoryInterface;
use App\Core\Interaction\ActionApplier;
use App\Core\Interaction\InteractionService;
use App\Core\Script\ScriptRepositoryInterface;
use SimpleTelegramBotClient\Dto\Type\Message;
use SimpleTelegramBotClient\TelegramService;

class RunGameStep implements StepInterface
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
     * RunGameStep constructor.
     * @param GameRepositoryInterface $gameRepository
     * @param ScriptRepositoryInterface $scriptRepository
     * @param InteractionService $interactionService
     * @param ResponseConverter $responseConverter
     * @param TelegramService $telegramService
     * @param AnswerRepository $answerRepository
     * @param ActionApplier $actionApplier
     */
    public function __construct(
        GameRepositoryInterface $gameRepository,
        ScriptRepositoryInterface $scriptRepository,
        InteractionService $interactionService,
        ResponseConverter $responseConverter,
        TelegramService $telegramService,
        AnswerRepository $answerRepository,
        ActionApplier $actionApplier
    ) {
        $this->gameRepository = $gameRepository;
        $this->scriptRepository = $scriptRepository;
        $this->interactionService = $interactionService;
        $this->responseConverter = $responseConverter;
        $this->telegramService = $telegramService;
        $this->answerRepository = $answerRepository;
        $this->actionApplier = $actionApplier;
    }

    public function run(User $user, Message $message): void
    {
        $currentScriptId = $user->getContext()->getCurrentScript();
        $gameId = $user->getContext()->getCurrentGame();

        $game = $this->gameRepository->findById($gameId);
        if (!$currentScriptId) {
            $user->runGame($gameId);
            $script = $this->scriptRepository->getScript($game, ScriptRepositoryInterface::FIRST_STEP);
        } else {
            $currentScript = $this->scriptRepository->getScript($game, $currentScriptId);
            $answer = $this->getAnswer($message, $currentScript);
            if ($answer) {
                $this->actionApplier->apply($answer->getAction());
                $script = $this->scriptRepository->findNextScript($game, $currentScript);
            } else {
                $script = $currentScript;
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
            return new Answer($script, '...', null);
        }
        return null;
    }
}
