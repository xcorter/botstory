<?php

namespace App\Web\Controller\Admin;

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
     * @Route("/admin/game/node/{questionId}", methods={"POST"}, name="update_node")
     * @return JsonResponse
     */
    public function update(int $questionId, Request $request)
    {
        $json = $request->getContent();
        if (!$json) {
            $this->logger->error("Wrong json");
            return JsonResponse::create();
        }
        $this->questionService->updateQuestion($questionId, $json);
        return JsonResponse::create();
    }
}