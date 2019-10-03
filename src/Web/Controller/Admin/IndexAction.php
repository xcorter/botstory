<?php

namespace App\Web\Controller\Admin;

use App\Core\Game\GameRepositoryInterface;
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
     * @Route("/admin/")
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(): Response
    {
       $texts =  $this->gameRepository->findAll();

        return new Response(
            $this->twig->render('@web/index.html.twig', [
                'text' => 'this is template',
                'texts' => $texts
            ])
        );
    }


}
