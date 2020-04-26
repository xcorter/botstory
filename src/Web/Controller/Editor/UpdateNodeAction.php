<?php

namespace App\Web\Controller\Editor;

use App\Core\Admin\Game\QuestionService;
use App\Core\Game\Entity\Game;
use App\Web\Security\GrantsChecker;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UpdateNodeAction
{
    /**
     * @var QuestionService
     */
    private $questionService;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var GrantsChecker
     */
    private $grantsChecker;

    /**
     * UpdateNodeAction constructor.
     * @param QuestionService $questionService
     * @param LoggerInterface $logger
     * @param GrantsChecker $grantsChecker
     */
    public function __construct(QuestionService $questionService, LoggerInterface $logger, GrantsChecker $grantsChecker)
    {
        $this->questionService = $questionService;
        $this->logger = $logger;
        $this->grantsChecker = $grantsChecker;
    }

    /**
     * @Route("/editor/game/{id}/node/", methods={"POST"}, name="update_node")
     * @ParamConverter("game", class="App\Core\Game\Entity\Game")
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Game $game, Request $request): JsonResponse
    {
        $json = $request->getContent();
        if (!$json) {
            $this->logger->error("Wrong json");
            return JsonResponse::create();
        }
        $this->grantsChecker->denyAccessUnlessGranted(Game::ACTION_EDIT, $game);
        $question = $this->questionService->updateQuestion($game, $json);
        return JsonResponse::create([
            'data' => $question->toArray(),
        ]);
    }
}
