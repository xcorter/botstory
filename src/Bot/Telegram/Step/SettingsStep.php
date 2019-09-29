<?php

namespace App\Bot\Telegram\Step;

use App\Bot\Telegram\Transform\ResponseConverter;
use App\Core\Entity\User;
use App\Core\Interaction\InteractionService;
use SimpleTelegramBotClient\Dto\Type\Message;
use SimpleTelegramBotClient\TelegramService;

class SettingsStep implements StepInterface
{
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
     * SettingsStep constructor.
     * @param InteractionService $interactionService
     * @param ResponseConverter $responseConverter
     * @param TelegramService $telegramService
     */
    public function __construct(
        InteractionService $interactionService,
        ResponseConverter $responseConverter,
        TelegramService $telegramService
    ) {
        $this->interactionService = $interactionService;
        $this->responseConverter = $responseConverter;
        $this->telegramService = $telegramService;
    }

    public function run(User $user, Message $message): void
    {
        $user->selectSettingsMenu();
        $interactionResponse = $this->interactionService->showSettings($message->getChat()->getId());
        $sendMessage = $this->responseConverter->convertToTelegramMessage($interactionResponse);
        $this->telegramService->sendMessage($sendMessage);
    }
}
