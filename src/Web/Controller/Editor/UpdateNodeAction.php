<?php

namespace App\Web\Controller\Editor;

use App\Core\Admin\Game\QuestionService;
use Psr\Log\LoggerInterface;
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
     * UpdateQuestion constructor.
     * @param QuestionService $questionService
     * @param LoggerInterface $logger
     */
    public function __construct(QuestionService $questionService, LoggerInterface $logger)
    {
        $this->questionService = $questionService;
        $this->logger = $logger;
    }

    /**
     * @Route("/editor/game/{gameId}/node/", methods={"POST"}, name="update_node")
     * @param int $gameId
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $gameId, Request $request): JsonResponse
    {
        $json = $request->getContent();
        if (!$json) {
            $this->logger->error("Wrong json");
            return JsonResponse::create();
        }
        $question = $this->questionService->updateQuestion($gameId, $json);
        return JsonResponse::create([
            'data' => $question->toArray(),
        ]);
    }
}
