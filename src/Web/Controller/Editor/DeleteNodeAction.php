<?php

declare(strict_types=1);

namespace App\Web\Controller\Editor;

use App\Core\Admin\Game\QuestionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DeleteNodeAction
{
    /**
     * @var QuestionService
     */
    private $questionService;

    /**
     * DeleteNodeAction constructor.
     * @param QuestionService $questionService
     */
    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }


    /**
     * @Route("/editor/game/{gameId}/node/{questionId}", methods={"DELETE"}, name="delete_node")
     * @param int $gameId
     * @param int $questionId
     * @return JsonResponse
     */
    public function __invoke(int $gameId, int $questionId): JsonResponse
    {
        $this->questionService->deleteQuestion($gameId, $questionId);
        return JsonResponse::create([
            'data' => '',
        ]);
    }
}
