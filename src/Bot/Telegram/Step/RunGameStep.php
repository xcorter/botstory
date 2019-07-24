<?php

namespace App\Bot\Telegram\Step;

use App\Bot\Telegram\Transform\ResponseConverter;
use App\Bot\Telegram\Util\Helper;
use App\Core\Answer\AnswerRepository;
use App\Core\Entity\Answer;
use App\Core\Entity\User;
use App\Core\Game\GameRepository;
use App\Core\Interaction\ActionApplier;
use App\Core\Interaction\InteractionService;
use App\Core\Script\ScriptRepository;
use SimpleTelegramBotClient\Dto\Message;
use SimpleTelegramBotClient\TelegramService;

class RunGameStep implements StepInterface
{
    /**
     * @var GameRepository
     */
    private $gameRepository;
    /**
     * @var ScriptRepository
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
     * @param GameRepository $gameRepository
     * @param ScriptRepository $scriptRepository
     * @param InteractionService $interactionService
     * @param ResponseConverter $responseConverter
     * @param TelegramService $telegramService
     * @param AnswerRepository $answerRepository
     * @param ActionApplier $actionApplier
     */
    public function __construct(
        GameRepository $gameRepository,
        ScriptRepository $scriptRepository,
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
            $script = $this->scriptRepository->getScript($game, ScriptRepository::FIRST_STEP);
        } else {
            $currentScript = $this->scriptRepository->getScript($game, $currentScriptId);
            $answers = $this->answerRepository->findByScript($currentScript);
            $answer = $this->getAnswer($message, $answers);
            if (!$answer) {
                return;
            }
            $this->actionApplier->apply($answer->getAction());
            $nextScript = $currentScriptId + 1;
            $script = $this->scriptRepository->getScript($game, $nextScript);
        }

        $user->getContext()->setCurrentScript($script->getId());

        $chatId = $message->getChat()->getId();
        $interactionResponse = $this->interactionService->showScript($chatId, $script);
        $sendMessage = $this->responseConverter->convertToTelegramMessage($interactionResponse);
        $this->telegramService->sendMessage($sendMessage);
    }

    /**
     * @param Message $message
     * @param Answer[] $answers
     * @return Answer|null
     */
    private function getAnswer(Message $message, array $answers): ?Answer
    {
        foreach ($answers as $answer) {
            if ($answer->getText() === Helper::trim($message->getText())) {
                return $answer;
            }
        }
        return null;
    }
}
