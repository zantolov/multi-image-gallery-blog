<?php


namespace App\Service;

use League\Glide;

class GlideServer
{
    private $server;

    public function __construct(FileManager $fm)
    {
        $this->server = $server = Glide\ServerFactory::create([
            'source' => $fm->getUploadsDirectory(),
            'cache' => $fm->getUploadsDirectory().'/cache',
        ]);
    }

    public function getGlide()
    {
        return $this->server;
    }
}