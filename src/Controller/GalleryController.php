<?php

namespace App\Controller;

use App\Entity\Gallery;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Environment;

class GalleryController
{
    /** @var  Twig_Environment */
    private $twig;

    /** @var  EntityManager */
    private $em;

    /**
     * GalleryController constructor.
     * @param Twig_Environment $twig
     * @param EntityManager $em
     */
    public function __construct(Twig_Environment $twig, EntityManager $em)
    {
        $this->twig = $twig;
        $this->em = $em;
    }

    /**
     * @Route("/gallery/{id}", name="gallery.single-gallery")
     */
    public function homeAction($id)
    {
        $gallery = $this->em->getRepository(Gallery::class)->find($id);
        if (empty($gallery)) {
            throw new NotFoundHttpException();
        }

        $view = $this->twig->render('gallery/single-gallery.html.twig', [
            'gallery' => $gallery,
        ]);

        return new Response($view);
    }

}