<?php

namespace App\Bot\Telegram\Step;

use App\Bot\Telegram\Util\Helper;
use App\Core\Entity\User;
use App\Core\Game\GameRepositoryInterface;
use SimpleTelegramBotClient\Dto\Type\Message;

class GameSelectedStep implements StepInterface
{
    /**
     * @var GameRepositoryInterface
     */
    private $gameRepository;
    /**
     * @var RunGameStep
     */
    private $runGameStep;

    /**
     * GameSelectedStep constructor.
     * @param GameRepositoryInterface $gameRepository
     * @param RunGameStep $runGameStep
     */
    public function __construct(GameRepositoryInterface $gameRepository, RunGameStep $runGameStep)
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