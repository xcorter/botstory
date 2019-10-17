<?php

namespace App\Core\Mode;

use App\Bot\Telegram\Util\Helper;
use App\Core\Entity\User;
use App\Core\Game\GameRepositoryInterface;
use SimpleTelegramBotClient\Dto\Type\Message;

class GameSelectedMode implements ModeInterface
{
    /**
     * @var GameRepositoryInterface
     */
    private $gameRepository;
    /**
     * @var RunGameMode
     */
    private $runGameStep;

    /**
     * GameSelectedStep constructor.
     * @param GameRepositoryInterface $gameRepository
     * @param RunGameMode $runGameStep
     */
    public function __construct(GameRepositoryInterface $gameRepository, RunGameMode $runGameStep)
    {
        $this->gameRepository = $gameRepository;
        $this->runGameStep = $runGameStep;
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
        $user->runGame($game->getId());
        $this->runGameStep->run($user, $message);
    }

}
