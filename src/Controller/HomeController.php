<?php

namespace App\Controller;

use App\Entity\Gallery;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Environment;

class HomeController
{
    const PER_PAGE = 12;

    /** @var  Twig_Environment */
    private $twig;

    /** @var  EntityManager */
    private $em;

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

    /**
     * @Route("/galleries-lazy-load", name="home.lazy-load")
     */
    public function homeGalleriesLazyLoadAction(Request $request)
    {
        $page = $request->get('page', null);
        if (empty($page)) {
            return new JsonResponse([
                'success' => false,
                'msg'     => 'Page param is required',
            ]);
        }

        $offset = ($page - 1) * self::PER_PAGE;
        $galleries = $this->em->getRepository(Gallery::class)->findBy([], ['createdAt' => 'DESC'], 12, $offset);

        $view = $this->twig->render('partials/home-galleries-lazy-load.html.twig', [
            'galleries' => $galleries,
        ]);

        return new JsonResponse([
            'success' => true,
            'data'    => $view,
        ]);
    }


}
