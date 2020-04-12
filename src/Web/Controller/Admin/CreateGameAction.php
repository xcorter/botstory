<?php

namespace App\Web\Controller\Admin;

use App\Core\Game\Entity\Game;
use App\Core\Game\GameRepositoryInterface;
use App\Editor\Form\FormFactory;
use App\Editor\Form\GameForm;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateGameAction
{
    /**
     * @var GameRepositoryInterface
     */
    private $gameRepository;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * CreateGameAction constructor.
     * @param GameRepositoryInterface $gameRepository
     * @param UrlGeneratorInterface $router
     */
    public function __construct(GameRepositoryInterface $gameRepository, UrlGeneratorInterface $router)
    {
        $this->gameRepository = $gameRepository;
        $this->router = $router;
    }

    /**
     * @Route("/admin/create/", methods={"POST"}, name="create_game")
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $formFactory = FormFactory::createFormFactory();
        $form = $formFactory->create(GameForm::class, new Game('', ''));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Game $game */
            $game = $form->getData();
            $this->gameRepository->save($game);
            return new RedirectResponse($this->router->generate('admin_main_page'));
        }
        return new Response([
            'form' => $form->createView()
        ]);
    }
}