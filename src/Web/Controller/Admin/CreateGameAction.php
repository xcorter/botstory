<?php

namespace App\Web\Controller\Admin;

use App\Core\Game\GameService;
use App\Core\User\Entity\User;
use App\Web\DTO\GameDTO;
use App\Web\Form\FormFactory;
use App\Web\Form\GameForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CreateGameAction
{
    private UrlGeneratorInterface $router;
    private GameService $gameService;
    private TokenStorageInterface $tokenStorage;

    /**
     * CreateGameAction constructor.
     * @param UrlGeneratorInterface $router
     * @param GameService $gameService
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(UrlGeneratorInterface $router, GameService $gameService, TokenStorageInterface $tokenStorage)
    {
        $this->router = $router;
        $this->gameService = $gameService;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/admin/create/", methods={"POST"}, name="create_game")
     * @return Response
     */
    public function __invoke(Request $request, UserInterface $user = null)
    {
        if ($user === null || !$user instanceof User) {
            throw new \RuntimeException('User is null');
        }
        $formFactory = FormFactory::createFormFactory();
        $form = $formFactory->create(GameForm::class, new GameDTO($user));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var GameDTO $game */
            $game = $form->getData();
            $this->gameService->save($game);
            return new RedirectResponse($this->router->generate('admin_main_page'));
        }
        return new RedirectResponse($this->router->generate('create_game_form'));
    }
}