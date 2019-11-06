<?php

namespace App\Core\Mode;

use App\Core\Entity\User;
use SimpleTelegramBotClient\Dto\Type\Message;

class GameOverMode implements ModeInterface
{

    public function run(User $user, Message $message): void
    {
        // TODO: Implement run() method.
    }
}
