<?php

namespace App\Bot\Telegram\Step;

use App\Bot\Telegram\Util\Helper;
use App\Core\Entity\User;
use App\Core\Game\GameRepository;
use SimpleTelegramBotClient\Dto\Type\Message;

class GameSelectedStep implements StepInterface
{
    /**
     * @var GameRepository
     */
    private $gameRepository;
    /**
     * @var RunGameStep
     */
    private $runGameStep;

    /**
     * GameSelectedStep constructor.
     * @param GameRepository $gameRepository
     * @param RunGameStep $runGameStep
     */
    public function __construct(GameRepository $gameRepository, RunGameStep $runGameStep)
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
