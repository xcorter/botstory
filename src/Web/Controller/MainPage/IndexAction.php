<?php

namespace App\Web\Controller\MainPage;

use App\Core\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class IndexAction
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
     * @Route("/")
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(): Response
    {
        return new Response(
            $this->twig->render('@web/base.html.twig', [
                'text' => 'this is template'
            ])
        );
    }
}
