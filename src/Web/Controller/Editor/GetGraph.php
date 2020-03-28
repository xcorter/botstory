<?php

namespace App\Web\Controller\Editor;

use App\Core\Admin\Game\GameService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetGraph
{

    /**
     * @var GameService
     */
    private $gameService;

    /**
     * GetGraph constructor.
     * @param GameService $gameService
     */
    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * @Route("/editor/game/{gameId}/graph", name="graph_game")
     * @return JsonResponse
     */
    public function __invoke(int $gameId): JsonResponse
    {
        $data = $this->gameService->getGraph($gameId);

        return new JsonResponse($data);
    }
}
