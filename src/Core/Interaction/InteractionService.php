<?php

namespace App\Core\Interaction;

class InteractionService
{

    public function getInfo(string $chatId): InteractionResponse
    {
        $keyboard = [
            ['Показать все игры']
        ];
        $interactionResponse = new InteractionResponse($chatId, 'hello world! choose your destiny');
        $interactionResponse->setKeyboard($keyboard);
        return $interactionResponse;
    }
}
