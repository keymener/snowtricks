<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileDeleter
{


    private $uploadDirectory;

    public function __construct($uploadDirectory)
    {
        $this->uploadDirectory = $uploadDirectory;
    }


    public function remove($filename)
    {

        try {

            unlink($this->uploadDirectory . '/' . $filename);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}