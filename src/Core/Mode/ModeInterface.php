<?php

namespace App\Core\Mode;

use App\Core\Entity\Player;
use SimpleTelegramBotClient\Dto\Type\Message;

interface ModeInterface
{

    public function run(Player $user, Message $message): void;
}
