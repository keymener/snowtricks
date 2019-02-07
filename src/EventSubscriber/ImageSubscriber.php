<?php
/**
 * Created by PhpStorm.
 * User: keyme
 * Date: 07/02/2019
 * Time: 23:30
 */

namespace App\EventSubscriber;


use App\Entity\Image;
use App\Service\FileManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageSubscriber implements EventSubscriber
{

    private $uploader;

    public function __construct(FileManager $uploader)
    {
        $this->uploader = $uploader;
    }

    public function getSubscribedEvents()
    {

        return [
            Events::postRemove,
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function postRemove(LifecycleEventArgs $args)
    {
          /** @var Image $entity */
        $entity = $args->getEntity();
        $this->uploader->remove($entity->getName());
    }

    public function prePersist(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        dump($args);
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        if (!$entity instanceof Image) {
            return;
        }

        $file = $entity->getFile();

        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setName($fileName);
        } elseif ($file instanceof File) {
            $entity->setName($file->getFilename());
        }
    }
}