<?php

namespace App\Web\Controller\Admin;

use App\Core\Admin\Game\AnswerService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UpdateAnswer
{
    /**
     * @var AnswerService
     */
    private $answerService;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * UpdateQuestion constructor.
     * @param AnswerService $answerService
     * @param LoggerInterface $logger
     */
    public function __construct(AnswerService $answerService, LoggerInterface $logger)
    {
        $this->answerService = $answerService;
        $this->logger = $logger;
    }

    /**
     * @Route("/admin/game/answer/{answerId}", methods={"POST"}, name="update_link")
     * @param int $answerId
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $answerId, Request $request)
    {
        $json = $request->getContent();
        $fields = json_decode($json, true);
        if (!$fields) {
            $this->logger->error("Wrong json");
            return JsonResponse::create();
        }
        $this->answerService->updateAnswer($answerId, $fields);
        return JsonResponse::create();
    }
}