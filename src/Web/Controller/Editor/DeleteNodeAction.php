<?php

declare(strict_types=1);

namespace App\Web\Controller\Editor;

use App\Core\Admin\Game\QuestionService;
use App\Core\Game\Entity\Game;
use App\Core\Game\GameRepositoryInterface;
use App\Web\Security\GrantsChecker;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteNodeAction
{
    private QuestionService $questionService;
    private GameRepositoryInterface $gameRepository;
    private LoggerInterface $logger;
    private GrantsChecker $grantsChecker;

    /**
     * DeleteNodeAction constructor.
     * @param QuestionService $questionService
     * @param GameRepositoryInterface $gameRepository
     * @param LoggerInterface $logger
     * @param GrantsChecker $grantsChecker
     */
    public function __construct(QuestionService $questionService, GameRepositoryInterface $gameRepository, LoggerInterface $logger, GrantsChecker $grantsChecker)
    {
        $this->questionService = $questionService;
        $this->gameRepository = $gameRepository;
        $this->logger = $logger;
        $this->grantsChecker = $grantsChecker;
    }

    /**
     * @Route("/editor/game/{id}/node/{questionId}", methods={"DELETE"}, name="delete_node")
     * @ParamConverter("game", class="App\Core\Game\Entity\Game")
     * @param Game $game
     * @param int $questionId
     * @return JsonResponse
     */
    public function __invoke(Game $game, int $questionId): JsonResponse
    {
        $this->grantsChecker->denyAccessUnlessGranted(Game::ACTION_EDIT, $game);
        $this->questionService->deleteQuestion($game, $questionId);
        return JsonResponse::create([
            'data' => '',
        ]);
    }
}
