<?php

namespace App\Controller;

use App\Entity\Gallery;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Environment;

class HomeController
{
    /** @var  Twig_Environment */
    private $twig;

    /** @var  EntityManager */
    private $em;

    /**
     * HomeController constructor.
     * @param Twig_Environment $twig
     * @param EntityManager $em
     */
    public function __construct(Twig_Environment $twig, EntityManager $em)
    {
        $this->twig = $twig;
        $this->em = $em;
    }

    /**
     * @Route("", name="home")
     */
    public function homeAction()
    {
        $galleries = $this->em->getRepository(Gallery::class)->findBy([], ['createdAt' => 'DESC']);
        $view = $this->twig->render('home.html.twig', [
            'galleries' => $galleries,
        ]);

        return new Response($view);
    }

}