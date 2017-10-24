<?php

namespace App\Twig;

use App\Entity\Image;
use Symfony\Component\Routing\RouterInterface;
use Twig_Extension;
use Twig_SimpleFilter;

class ImageRendererExtension extends Twig_Extension
{
    /** @var  RouterInterface */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('getImageUrl', [$this, 'getImageUrl']),
        ];
    }

    public function getImageUrl(Image $image)
    {
        return $this->router->generate('image.serve', [
            'id' => $image->getId(),
        ], RouterInterface::ABSOLUTE_URL);
    }

}