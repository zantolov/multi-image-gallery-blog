<?php

namespace App\Controller;

use App\Entity\Image;
use App\Service\FileManager;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageController
{
    /** @var EntityManager */
    private $em;

    /** @var  FileManager */
    private $fileManager;

    public function __construct(EntityManager $em, FileManager $fileManager)
    {
        $this->em = $em;
        $this->fileManager = $fileManager;
    }

    /**
     * @Route("/image/{id}/raw", name="image.serve")
     */
    public function serveImageAction(Request $request, $id)
    {
        $image = $this->em->getRepository(Image::class)->find($id);

        if (empty($image)) {
            throw new NotFoundHttpException('Image not found');
        }

        $fullPath = $this->fileManager->getFilePath($image->getFilename());
        $response = new BinaryFileResponse($fullPath);
        $response->headers->set('Content-type', mime_content_type($fullPath));
        $response->headers->set('Content-Disposition',
            'attachment; filename="' . $image->getOriginalFilename() . '";');

        return $response;

    }

}
