<?php

namespace App\Web\Controller\Landing;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class IndexAction
{
    private Environment $twig;

    /**
     * IndexAction constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="index")
     */
    public function __invoke()
    {
        return new Response(
            $this->twig->render('@web/landing/index.html.twig')
        );
    }
}