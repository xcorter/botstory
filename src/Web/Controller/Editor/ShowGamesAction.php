<?php

namespace App\Web\Controller\Editor;

use App\Core\Game\Entity\Game;
use App\Web\Security\GrantsChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ShowGamesAction
{
    private Environment $twig;
    private GrantsChecker $grantsChecker;

    /**
     * ShowGamesAction constructor.
     * @param Environment $twig
     * @param GrantsChecker $grantsChecker
     */
    public function __construct(Environment $twig, GrantsChecker $grantsChecker)
    {
        $this->twig = $twig;
        $this->grantsChecker = $grantsChecker;
    }

    /**
     * @Route("/editor/game/{id}", name="game")
     * @ParamConverter("game", class="App\Core\Game\Entity\Game")
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Game $game)
    {
        $this->grantsChecker->denyAccessUnlessGranted(Game::ACTION_EDIT, $game);
        return new Response(
            $this->twig->render('@web/game.html.twig', [
                'game' => $game
            ])
        );
    }
}
