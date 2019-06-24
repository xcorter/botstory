<?php

namespace App\Web\Controller\Admin;


use App\Core\Entity\Script;
use App\Web\Form\ScriptType;


use App\Core\Repository\GameRepository;
use App\Core\Repository\ScriptRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilderInterface;
use Twig\Environment;

class AddScript extends AbstractController
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
     * @var ScriptRepository
     */
    private $scriptRepository;

    /**
     * IndexAction constructor.
     * @param Environment $twig
     * @param GameRepository $gameRepository
     * @param ScriptRepository $scriptRepository
     */
    public function __construct(
        Environment $twig,
        ScriptRepository $scriptRepository,
        GameRepository $gameRepository

    )
    {
        $this->twig = $twig;
        $this->scriptRepository = $scriptRepository;
        $this->gameRepository = $gameRepository;

    }

    /**
     * @Route("/admin/game/{gameId}/addScript", name="addScript")
     * @param int $gameId
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showNewScriptFields(int $gameId): Response
    {
        $game = $this->gameRepository->findGameById($gameId);

        if (!$game) {
            $this->logger->error('Game not found');
            throw new \OutOfRangeException('Game object not found');
        }

        $script = new Script('', 25, 34);
        $script->setGame($game);
        $script->setStep('245');
        $script->setText('Here is my new Text');


        $form = $this->createForm(ScriptType::class, $script);

        return new Response(
            $this->twig->render(
                '@web/add_script.html.twig',
                [
                    'post_form'=>$form->createView()

                ]
            )
        );

    }

    /**
     * @Route("/admin/game/{gameId}/addScript", name="createScript", methods={"POST"})
     * @param int $gameId
     * @return Response
     * @return Request
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function saveData(int $gameId, Request $request)
    {
        $game = $this->gameRepository->findGameById($gameId);

        $script = new Script('sdsdf', 34, 3);


        $form = $this->createForm(ScriptType::class, $script,
            [
            'action' => $this->generateUrl('createScript')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {


            $text = $form['text']->getData();
            $step = $form['step']->getData();

            $script->setGame($gameId);
            $script->setText($text);
            $script->setStep($step);

           $em =  $this->getDoctrine()->getManager();
            $em->persist($script);
            $em->flush();
        }

        return new Response(
            $this->twig->render(
                '@web/add_script.html.twig',
                [
                    'post_form'=>$form->createView()
                ]
            )
        );

    }



}
