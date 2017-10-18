<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Environment;

class HomeController
{
    /** @var  Twig_Environment */
    private $twig;

    /**
     * HomeController constructor.
     * @param $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("", name="home")
     */
    public function homeAction()
    {
        $view = $this->twig->render('home.html.twig');

        return new Response($view);
    }

}