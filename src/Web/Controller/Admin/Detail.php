<?php

namespace App\Web\Controller\Admin;

use App\Core\Game\GameRepository;
use App\Core\Script\ScriptRepository;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class Detail
{

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @var ScriptRepository
     */
    private $scriptRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * IndexAction constructor.
     * @param Environment $twig
     * @param GameRepository $gameRepository
     * @param ScriptRepository $scriptRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Environment $twig,
        GameRepository $gameRepository,
        ScriptRepository $scriptRepository,
        LoggerInterface $logger
    ) {
        $this->twig = $twig;
        $this->gameRepository = $gameRepository;
        $this->scriptRepository = $scriptRepository;
        $this->logger = $logger;
    }

    /**
     * @Route("/admin/game/{gameId}", name="game")
     * @param int $gameId
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showGame(int $gameId, Request $request, PaginatorInterface $paginator): Response
    {
        $game = $this->gameRepository->findGameById($gameId);

        if (!$game) {
            $this->logger->error('Game not found');
            throw new \OutOfRangeException('Game object not found');
        }

        $scriptsQuery = $this->scriptRepository->getPaginatorQuery($game->getId());

        // Paginate the results of the query
        $scripts = $paginator->paginate(
        // Doctrine Query, not results
            $scriptsQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            20
        );
        return new Response(
            $this->twig->render(
                '@web/game.html.twig',
                [
                    'game' => $game,
                    'scripts' => $scripts
                ]
            )
        );
    }
}
