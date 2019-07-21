<?php

namespace App\Bot\Telegram\Step;

use App\Core\Entity\User;
use SimpleTelegramBotClient\Dto\Message;

interface StepInterface
{

    public function run(User $user, Message $message): void;
}
