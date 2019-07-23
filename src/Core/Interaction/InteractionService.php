<?php

namespace App\Core\Interaction;

use App\Core\Entity\Game;
use App\Core\Entity\Script;

class InteractionService
{

    public function getInfo(string $chatId): InteractionResponse
    {
        $keyboard = [
            [Command::SHOW_ALL_GAMES]
        ];
        $interactionResponse = new InteractionResponse($chatId, 'hello world! choose your destiny');
        $interactionResponse->setKeyboard($keyboard);
        return $interactionResponse;
    }

    /**
     * @param string $chatId
     * @param Game[] $games
     * @return InteractionResponse
     */
    public function showAllGames(string $chatId, array $games): InteractionResponse
    {
        $keyboard = [];
        foreach ($games as $game) {
            $keyboard[] = [$game->getName()];
        }
        $interactionResponse = new InteractionResponse($chatId, 'Выбери свою игру');
        $interactionResponse->setKeyboard($keyboard);
        return $interactionResponse;
    }

    public function showScript(string $chatId, Script $script): InteractionResponse
    {
        $interactionResponse = new InteractionResponse($chatId, $script->getText());
        $answers = $script->getAnswers();
        if ($answers) {
            $interactionResponse->setKeyboard($script->getAnswers());
        }
        return $interactionResponse;
    }
}
