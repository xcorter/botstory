<?php

namespace App\Core\Interaction;

use App\Core\Entity\Player;
use App\Core\Game\GameContextRepositoryInterface;
use App\Core\Game\GameRepositoryInterface;
use App\Core\Interaction\Action\ActionFactory;

class ActionApplier
{
    private ActionFactory $actionFactory;
    private GameContextRepositoryInterface $gameContextRepository;
    private GameRepositoryInterface $gameRepository;
    
    public function __construct(ActionFactory $actionFactory, GameContextRepositoryInterface $gameContextRepository, GameRepositoryInterface $gameRepository)
    {
        $this->actionFactory = $actionFactory;
        $this->gameContextRepository = $gameContextRepository;
        $this->gameRepository = $gameRepository;
    }

    public function apply(Player $user, array $actionParams): void
    {
        $gameId = $user->getContext()->getCurrentGame();
        $game = $this->gameRepository->findById($gameId);
        $gameContext = $this->gameContextRepository->findGameContext(
            $user,
            $game
        );
        if (!$gameContext) {
            new \RuntimeException('game context not found');
        }
        foreach ($actionParams as $actionParam) {
            $action = $this->actionFactory->createAction($actionParam);
            $action->execute($gameContext);
        }
        $this->gameContextRepository->save($gameContext);
    }


}
