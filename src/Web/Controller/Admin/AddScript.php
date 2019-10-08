<?php

namespace App\Web\Controller\Admin;

use App\Core\Entity\Script;
use App\Core\Game\GameRepositoryInterface;
use App\Core\Script\ScriptRepositoryInterface;
use App\Web\Form\ScriptType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class AddScript extends AbstractController
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
     * @var ScriptRepositoryInterface
     */
    private $scriptRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AddScript constructor.
     * @param Environment $twig
     * @param GameRepositoryInterface $gameRepository
     * @param ScriptRepositoryInterface $scriptRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Environment $twig,
        GameRepositoryInterface $gameRepository,
        ScriptRepositoryInterface $scriptRepository,
        LoggerInterface $logger
    ) {
        $this->twig = $twig;
        $this->gameRepository = $gameRepository;
        $this->scriptRepository = $scriptRepository;
        $this->logger = $logger;
    }


    /**
     * @Route("/admin/game/{gameId}/addScript", name="addScript", methods={"GET"})
     * @param int $gameId
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showNewScriptFields(int $gameId): Response
    {
        $game = $this->gameRepository->findById($gameId);

        if (!$game) {
            $this->logger->error('Game not found');
            throw new \OutOfRangeException('Game object not found');
        }

        $form = $this->createForm(ScriptType::class, null, [
            'game_entity' => $game
        ]);

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
        $form = $this->createForm(ScriptType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Script $script */
            $script = $form->getData();
            $this->scriptRepository->save($script);
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
