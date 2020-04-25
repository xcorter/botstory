<?php

namespace App\Core\Mode;

use App\Core\Entity\Player;
use SimpleTelegramBotClient\Dto\Type\Message;

class GameOverMode implements ModeInterface
{

    public function run(Player $user, Message $message): void
    {
        // TODO: Implement run() method.
    }
}
