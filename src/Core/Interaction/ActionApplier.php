<?php

namespace App\Core\Interaction;

use App\Core\Entity\User;
use App\Core\Game\GameContextRepositoryInterface;
use App\Core\Game\GameRepositoryInterface;
use App\Core\Interaction\Action\ActionFactory;

class ActionApplier
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;
    /**
     * @var GameContextRepositoryInterface
     */
    private $gameContextRepository;
    /**
     * @var GameRepositoryInterface
     */
    private $gameRepository;

    /**
     * ActionApplier constructor.
     * @param ActionFactory $actionFactory
     * @param GameContextRepositoryInterface $gameContextRepository
     * @param GameRepositoryInterface $gameRepository
     */
    public function __construct(ActionFactory $actionFactory, GameContextRepositoryInterface $gameContextRepository, GameRepositoryInterface $gameRepository)
    {
        $this->actionFactory = $actionFactory;
        $this->gameContextRepository = $gameContextRepository;
        $this->gameRepository = $gameRepository;
    }

    public function apply(User $user, $actionParams): void
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
        $actionParams = json_decode($actionParams, true);
        foreach ($actionParams as $actionParam) {
            $action = $this->actionFactory->createAction($actionParam);
            $action->execute($gameContext);
        }
        $this->gameContextRepository->save($gameContext);
    }


}
