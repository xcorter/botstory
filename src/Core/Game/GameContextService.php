<?php

namespace App\Core\Game;

use App\Core\Entity\User;
use App\Core\Game\Entity\Game;
use App\Core\Game\Entity\GameContext;

class GameContextService
{
    /**
     * @var CharacteristicRepositoryInterface
     */
    private $characteristicRepository;
    /**
     * @var CharacteristicService
     */
    private $characteristicService;
    /**
     * @var GameContextRepositoryInterface
     */
    private $gameContextRepository;

    /**
     * GameContextService constructor.
     * @param CharacteristicRepositoryInterface $characteristicRepository
     * @param CharacteristicService $characteristicService
     * @param GameContextRepositoryInterface $gameContextRepository
     */
    public function __construct(
        CharacteristicRepositoryInterface $characteristicRepository,
        CharacteristicService $characteristicService,
        GameContextRepositoryInterface $gameContextRepository
    ) {
        $this->characteristicRepository = $characteristicRepository;
        $this->characteristicService = $characteristicService;
        $this->gameContextRepository = $gameContextRepository;
    }

    public function createGameContext(User $user, Game $game): void
    {
        $characteristics = $this->characteristicRepository->findByGame($game);
        $serializeArray = $this->characteristicService->serializeArray($characteristics);
        $gameContext = new GameContext($user, $game, json_encode($serializeArray));
        $this->gameContextRepository->save($gameContext);
    }

    public function removeGameContext(User $user, Game $game): void
    {
        $gameContext = $this->gameContextRepository->findGameContext($user, $game);
        if ($gameContext) {
            $this->gameContextRepository->delete($gameContext);
        }
    }
}
