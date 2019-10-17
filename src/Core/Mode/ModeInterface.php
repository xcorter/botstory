<?php

namespace App\Core\Mode;

use App\Core\Entity\User;
use SimpleTelegramBotClient\Dto\Type\Message;

interface ModeInterface
{

    public function run(User $user, Message $message): void;
}
