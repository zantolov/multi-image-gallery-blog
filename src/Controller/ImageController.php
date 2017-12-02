<?php

namespace App\Controller;

use App\Entity\Image;
use App\Service\FileManager;
use App\Service\GlideServer;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageController extends Controller
{
    /** @var EntityManager */
    private $em;

    /** @var  FileManager */
    private $fileManager;

    public function __construct(EntityManager $em, FileManager $fileManager)
    {
        $this->em          = $em;
        $this->fileManager = $fileManager;
    }
//
//    /**
//     * @Route("/image/{id}/raw", name="image.serve")
//     */
//    public function serveImageAction(Request $request, $id, GlideServer $glide)
//    {
//        $idFragments = explode('--', $id);
//        $id          = $idFragments[0];
//        $size        = $idFragments[1] ?? null;
//
//        $image = $this->em->getRepository(Image::class)->find($id);
//
//        if (empty($image)) {
//            throw new NotFoundHttpException('Image not found');
//        }
//
//        $fullPath = $this->fileManager->getFilePath($image->getFilename());
//
//        if ($size) {
//
//            $info        = pathinfo($fullPath);
//            $file        = $info['filename'] . '.' . $info['extension'];
//            $newfile     = $info['filename'] . '-' . $size . '.' . $info['extension'];
//            $fullPathNew = str_replace($file, $newfile, $fullPath);
//
//            if (file_exists($fullPath) && ! file_exists($fullPathNew)) {
//
//                $fullPath = $fullPathNew;
//                $img      = $glide->getGlide()->getImageAsBase64($file,
//                    ['w' => $size]);
//
//                $ifp = fopen($fullPath, 'wb');
//
//                $data = explode(',', $img);
//                fwrite($ifp, base64_decode($data[1]));
//                fclose($ifp);
//            }
//        }
//
//        $response = new BinaryFileResponse($fullPath);
//        $response->headers->set('Content-type',
//            mime_content_type($fullPath));
//        $response->headers->set('Content-Disposition',
//            'attachment; filename="' . $image->getOriginalFilename() . '";');
//
//        return $response;
//
//
//    }
//
    /**
     * @Route("/image/{id}/raw", name="image.serve")
     */
    public function serveImageAction(Request $request, $id, GlideServer $glide)
    {
        $idFragments = explode('--', $id);
        $id          = $idFragments[0];
        $size        = $idFragments[1] ?? null;

        $image = $this->em->getRepository(Image::class)->find($id);

        if (empty($image)) {
            throw new NotFoundHttpException('Image not found');
        }

        $fullPath = $this->fileManager->getFilePath($image->getFilename());

        if ($size) {

            $info        = pathinfo($fullPath);
            $file        = $info['filename'] . '.' . $info['extension'];

            $cachePath = $glide->getGlide()->makeImage($file, ['w' => $size]);
            $fullPath = str_replace($file, '/cache/' . $cachePath, $fullPath);
        }

        $response = new BinaryFileResponse($fullPath);
        $response->headers->set('Content-type',
            mime_content_type($fullPath));
        $response->headers->set('Content-Disposition',
            'attachment; filename="' . $image->getOriginalFilename() . '";');

        // cache for 2 weeks
        $response->setSharedMaxAge(1209600);
        // (optional) set a custom Cache-Control directive
        $response->headers->addCacheControlDirective('must-revalidate', true);
        
        return $response;


    }

}
