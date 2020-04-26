<?php

namespace App\Web\Controller\Editor;

use App\Core\Admin\Game\GameService;
use App\Core\Game\Entity\Game;
use App\Web\Security\GrantsChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetGraph
{
    /**
     * @var GameService
     */
    private $gameService;
    /**
     * @var GrantsChecker
     */
    private $grantsChecker;

    /**
     * GetGraph constructor.
     * @param GameService $gameService
     * @param GrantsChecker $grantsChecker
     */
    public function __construct(GameService $gameService, GrantsChecker $grantsChecker)
    {
        $this->gameService = $gameService;
        $this->grantsChecker = $grantsChecker;
    }

    /**
     * @Route("/editor/game/{id}/graph", name="graph_game")
     * @ParamConverter("game", class="App\Core\Game\Entity\Game")
     * @return JsonResponse
     */
    public function __invoke(Game $game): JsonResponse
    {
        $this->grantsChecker->denyAccessUnlessGranted(Game::ACTION_EDIT, $game);
        $data = $this->gameService->getGraph($game);

        return new JsonResponse($data);
    }
}
