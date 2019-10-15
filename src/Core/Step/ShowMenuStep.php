<?php

namespace App\Core\Step;

use App\Bot\Telegram\Transform\ResponseConverter;
use App\Core\Entity\User;
use App\Core\Interaction\InteractionService;
use SimpleTelegramBotClient\Dto\Type\Message;
use SimpleTelegramBotClient\TelegramService;

class ShowMenuStep implements StepInterface
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
     * ShowMenuStep constructor.
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
        $user->showMenuStep();
        $interactionResponse = $this->interactionService->getInfo($message->getChat()->getId());
        $sendMessage = $this->responseConverter->convertToTelegramMessage($interactionResponse);
        $this->telegramService->sendMessage($sendMessage);
    }
}
