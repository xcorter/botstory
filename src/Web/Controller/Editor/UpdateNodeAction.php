<?php

namespace App\Web\Controller\Editor;

use App\Core\Admin\Game\QuestionService;
use App\Core\Answer\AnswerRepositoryInterface;
use App\Core\Answer\Specification\QuestionIdSpecification;
use App\Core\Game\Entity\Game;
use App\Web\Security\GrantsChecker;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UpdateNodeAction
{
    private QuestionService $questionService;
    private LoggerInterface $logger;
    private GrantsChecker $grantsChecker;
    private SerializerInterface $serializer;

    /**
     * UpdateNodeAction constructor.
     * @param QuestionService $questionService
     * @param LoggerInterface $logger
     * @param GrantsChecker $grantsChecker
     * @param SerializerInterface $serializer
     */
    public function __construct(QuestionService $questionService, LoggerInterface $logger, GrantsChecker $grantsChecker, SerializerInterface $serializer)
    {
        $this->questionService = $questionService;
        $this->logger = $logger;
        $this->grantsChecker = $grantsChecker;
        $this->serializer = $serializer;
    }


    /**
     * @Route("/editor/game/{id}/node/", methods={"POST"}, name="update_node")
     * @ParamConverter("game", class="App\Core\Game\Entity\Game")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(Game $game, Request $request): JsonResponse
    {
        $json = $request->getContent();
        if (!$json) {
            $this->logger->error("Wrong json");
            return JsonResponse::create();
        }
        $this->grantsChecker->denyAccessUnlessGranted(Game::ACTION_EDIT, $game);
        $node = $this->questionService->updateQuestion($game, $json);
        return JsonResponse::create([
            'data' => json_decode($this->serializer->serialize($node, 'json'), true)
        ]);
    }
}
