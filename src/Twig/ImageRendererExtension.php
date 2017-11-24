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
            new Twig_SimpleFilter('getImageSrcset', [$this, 'getImageSrcset']),
        ];
    }

    public function getImageUrl(Image $image, $size = null)
    {
        return $this->router->generate('image.serve', [
            'id' => $image->getId() . (($size) ? '--' . $size : ''),
        ], RouterInterface::ABSOLUTE_URL);
    }


    public function getImageSrcset(Image $image)
    {
        $id = $image->getId();
        $sizes = [1120, 720, 400];
        $string = '';
        foreach ($sizes as $size) {
            $string .= $this->router->generate('image.serve', [
                'id' => $image->getId() . '--' . $size,
            ], RouterInterface::ABSOLUTE_URL).' '.$size.'w, ';
        }
        $string = trim($string, ', ');
        return html_entity_decode($string);
    }

}