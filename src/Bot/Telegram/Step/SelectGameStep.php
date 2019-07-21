<?php

namespace App\Bot\Telegram\Step;

use App\Bot\Telegram\Util\Helper;
use App\Core\Entity\User;
use App\Core\Game\GameRepository;
use SimpleTelegramBotClient\Dto\Message;

class SelectGameStep implements StepInterface
{
    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * SelectGameStep constructor.
     * @param GameRepository $gameRepository
     */
    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function run(User $user, Message $message): void
    {
        $text = $message->getText();
        if (!$text) {
            return;
        }
        $text = Helper::trim($text);

        $game = $this->gameRepository->findGameByName($text);
        if (!$game) {
            return;
        }

        $user->getContext()->selectGame($game->getId());
    }
}
