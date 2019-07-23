<?php

namespace App\Bot\Telegram\Step;

use App\Bot\Telegram\Util\Helper;
use App\Core\Entity\User;
use App\Core\Game\GameRepository;
use App\Core\Script\ScriptRepository;
use SimpleTelegramBotClient\Dto\Message;

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

    public function run(User $user, Message $message): void
    {
        $currentScript = $user->getContext()->getCurrentScript();
        $gameId = $user->getContext()->getCurrentGame();

        $game = $this->gameRepository->findById($gameId);
        if (!$currentScript) {
            $script = $this->scriptRepository->getScript($game, ScriptRepository::FIRST_STEP);
        } else {
            $nextScript = $currentScript + 1;
            $script = $this->scriptRepository->getScript($game, $nextScript);
        }

    }
}
