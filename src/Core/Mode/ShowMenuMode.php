<?php

namespace App\Core\Mode;

use App\Bot\Telegram\Transform\ResponseConverter;
use App\Core\Entity\Player;
use App\Core\Interaction\InteractionService;
use SimpleTelegramBotClient\Dto\Type\Message;
use SimpleTelegramBotClient\TelegramService;

class ShowMenuMode implements ModeInterface
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

    public function run(Player $user, Message $message): void
    {
        $user->showMenuStep();
        $interactionResponse = $this->interactionService->getInfo($message->getChat()->getId());
        $sendMessage = $this->responseConverter->convertToTelegramMessage($interactionResponse);
        $this->telegramService->sendMessage($sendMessage);
    }
}
