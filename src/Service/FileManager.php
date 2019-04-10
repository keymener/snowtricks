<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{

    private $uploadDirectory;

    /**
     * FileUploader constructor.
     * @param $uploadDirectory
     */
    public function __construct($uploadDirectory)
    {
        $this->uploadDirectory = $uploadDirectory;
    }

    /**
     * Generates a unique filname and upload it into specified folder and return the filename
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file)
    {

        $fileName = md5(uniqid()) . '.' . $file->guessExtension();


        try {

            $file->move($this->uploadDirectory, $fileName);
        } catch (FileException $e) {
            echo "File error: ".$e->getMessage();

        }

        return $fileName;
    }

    /**
     * @return mixed
     */
    public function getUploadDirectory()
    {
        return $this->uploadDirectory;
    }


    /**
     * @param $filename
     */
    public function remove($filename)
    {

        try {

            unlink($this->uploadDirectory . '/' . $filename);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

}

