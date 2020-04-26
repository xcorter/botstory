<?php

namespace App\Core\Interaction;

use App\Core\Answer\AnswerRepositoryInterface;
use App\Core\Answer\Specification\QuestionIdSpecification;
use App\Core\Game\Entity\Game;
use App\Core\Question\Entity\Question;

class InteractionService
{
    /**
     * @var AnswerRepositoryInterface
     */
    private $answerRepository;

    /**
     * InteractionService constructor.
     * @param AnswerRepositoryInterface $answerRepository
     */
    public function __construct(AnswerRepositoryInterface $answerRepository)
    {
        $this->answerRepository = $answerRepository;
    }

    /**
     * @param string $chatId
     * @return InteractionResponse
     */
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

    /**
     * @param string $chatId
     * @param Question $question
     * @return InteractionResponse
     */
    public function showQuestion(string $chatId, Question $question): InteractionResponse
    {
        $interactionResponse = new InteractionResponse($chatId, $question->getText());

        $answers = $this->answerRepository->satisfyBy(new QuestionIdSpecification($question->getId()));
        $keyboard = [];
        if ($answers) {
            foreach ($answers as $answer) {
                $keyboard[] = [$answer->getText()];
            }
        } else {
            $keyboard[] = ['...'];
        }
        $keyboard = $this->addSettings($keyboard);
        $interactionResponse->setKeyboard($keyboard);
        return $interactionResponse;
    }

    public function showSettings(string $chatId): InteractionResponse
    {
        $interactionResponse = new InteractionResponse($chatId, 'В меню настроек вы можете настроить игру');

        $interactionResponse->setKeyboard([
            [Command::BACK_TO_GAME],
            [Command::RESET_GAME],
            [Command::SHOW_ALL_GAMES],
        ]);
        return $interactionResponse;
    }

    /**
     * @param array $keyboard
     * @return array
     */
    private function addSettings(array $keyboard): array
    {
        $keyboard[] = [Command::SETTINGS];
        return $keyboard;
    }
}
