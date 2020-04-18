<?php

namespace App\Core\Game;

use App\Core\Game\Event\NewGame;
use App\Web\DTO\GameDTO;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class GameService
{
    /**
     * @var GameRepositoryInterface
     */
    private $gameRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * GameService constructor.
     * @param GameRepositoryInterface $gameRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(GameRepositoryInterface $gameRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->gameRepository = $gameRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(GameDTO $gameDTO)
    {
        $game = $gameDTO->toEntity();
        $this->gameRepository->save($game);
        $this->eventDispatcher->dispatch(new NewGame($game), NewGame::NAME);
    }
}