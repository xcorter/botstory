<?php

namespace App\Web\Controller\Admin;

use App\Editor\Form\FormFactory;
use App\Editor\Form\GameForm;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateGameFormAction
{

    /**
     * @var Environment
     */
    private $twig;

    /**
     * CreateGameFormAction constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/admin/create/", methods={"GET"}, name="create_game_form")
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke()
    {
        $formFactory = FormFactory::createFormFactory();
        $form = $formFactory->create(GameForm::class);
        return new Response(
            $this->twig->render('@web/admin/create_game.html.twig', [
                'form' => $form->createView()
            ])
        );
    }
}