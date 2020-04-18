<?php

namespace App\Web\Controller\Admin;

use App\Core\Game\GameService;
use App\Web\DTO\GameDTO;
use App\Web\Form\FormFactory;
use App\Web\Form\GameForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateGameAction
{

    /**
     * @var UrlGeneratorInterface
     */
    private $router;
    /**
     * @var GameService
     */
    private $gameService;

    /**
     * CreateGameAction constructor.
     * @param UrlGeneratorInterface $router
     * @param GameService $gameService
     */
    public function __construct(UrlGeneratorInterface $router, GameService $gameService)
    {
        $this->router = $router;
        $this->gameService = $gameService;
    }

    /**
     * @Route("/admin/create/", methods={"POST"}, name="create_game")
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $formFactory = FormFactory::createFormFactory();
        $form = $formFactory->create(GameForm::class, new GameDTO());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var GameDTO $game */
            $game = $form->getData();
            $this->gameService->save($game);
            return new RedirectResponse($this->router->generate('admin_main_page'));
        }
        return new Response([
            'form' => $form->createView()
        ]);
    }
}