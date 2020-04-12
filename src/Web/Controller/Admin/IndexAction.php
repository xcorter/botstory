<?php

namespace App\Web\Controller\Admin;

use App\Core\Game\GameRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class IndexAction
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
     * @Route("/admin/", name="admin_main_page")
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(): Response
    {
       $games =  $this->gameRepository->findAll();

        return new Response(
            $this->twig->render('@web/admin/index.html.twig', [
                'games' => $games
            ])
        );
    }


}
