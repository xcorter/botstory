<?php

namespace App\Web\Controller\MainPage;

use Symfony\Component\HttpFoundation\Response;
use App\Core\Repository\GameRepository;
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
     * IndexAction constructor.
     * @param Environment $twig
     * @param GameRepository $gameRepository
     */
    public function __construct(Environment $twig, GameRepository $gameRepository)
    {
        $this->twig = $twig;
        $this->gameRepository = $gameRepository;
    }

    /**
     * @Route("/{gameId}", name="game")
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showGame($gameId): Response
    {
        $game = $this->gameRepository->findGameById($gameId);

        return new Response(

            $this->twig->render('@web/game.html.twig',
            ['game' => $game])
        );
    }

}
