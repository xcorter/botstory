<?php

namespace App\Web\Controller\Admin;

use Psr\Log\LoggerInterface;
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * IndexAction constructor.
     * @param Environment $twig
     * @param GameRepository $gameRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Environment $twig,
        GameRepository $gameRepository,
        LoggerInterface $logger
    ) {
        $this->twig = $twig;
        $this->gameRepository = $gameRepository;
        $this->logger = $logger;
    }

    /**
     * @Route("/admin/game/{gameId}", name="game")
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showGame($gameId): Response
    {
        $game = $this->gameRepository->findGameById($gameId);
        if (!$game) {
            $this->logger->error('Game not found');
            throw new \OutOfRangeException('Game object not found');
        }

        return new Response(

            $this->twig->render('@web/game.html.twig',
            ['game' => $game])
        );
    }

}
