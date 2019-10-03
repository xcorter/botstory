<?php

namespace App\Bot\Telegram\Step;

use App\Bot\Telegram\Transform\ResponseConverter;
use App\Bot\Telegram\Util\Helper;
use App\Core\Entity\User;
use App\Core\Game\GameRepositoryInterface;
use App\Core\Interaction\Command;
use App\Core\Interaction\InteractionService;
use SimpleTelegramBotClient\Dto\Type\Message;
use SimpleTelegramBotClient\TelegramService;

class SelectGameStep implements StepInterface
{
    /**
     * @var GameRepositoryInterface
     */
    private $gameRepository;
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
     * SelectGameStep constructor.
     * @param GameRepositoryInterface $gameRepository
     * @param InteractionService $interactionService
     * @param ResponseConverter $responseConverter
     * @param TelegramService $telegramService
     */
    public function __construct(
        GameRepositoryInterface $gameRepository,
        InteractionService $interactionService,
        ResponseConverter $responseConverter,
        TelegramService $telegramService
    ) {
        $this->gameRepository = $gameRepository;
        $this->interactionService = $interactionService;
        $this->responseConverter = $responseConverter;
        $this->telegramService = $telegramService;
    }

    public function run(User $user, Message $message): void
    {
        $text = $message->getText();
        if (!$text) {
            return;
        }
        $text = Helper::trim($text);
        if ($text !== Command::SHOW_ALL_GAMES) {
            return;
        }
        $games = $this->gameRepository->findAll();

        $interactionResponse = $this->interactionService->showAllGames(
            $message->getChat()->getId(),
            $games
        );
        $sendMessage = $this->responseConverter->convertToTelegramMessage($interactionResponse);
        $this->telegramService->sendMessage($sendMessage);
        $user->selectGameStep();
    }
}
