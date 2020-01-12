<?php


namespace App\Web\Controller\Admin;


use App\Core\Game\GameRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ShowGamesAction
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var GameRepositoryInterface
     */
    private $gameRepository;

    /**
     * IndexAction constructor.
     * @param Environment $twig
     * @param GameRepositoryInterface $gameRepository
     */
    public function __construct(Environment $twig, GameRepositoryInterface $gameRepository)
    {
        $this->twig = $twig;
        $this->gameRepository = $gameRepository;
    }

    /**
     * @Route("/admin/game/{gameId}", name="game")
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(int $gameId)
    {
        $game = $this->gameRepository->findById($gameId);

        return new Response(
            $this->twig->render('@web/game.html.twig', [
                'game' => $game
            ])
        );
    }
}