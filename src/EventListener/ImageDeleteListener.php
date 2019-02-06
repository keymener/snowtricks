<?php

namespace App\EventListener;

use App\Entity\Image;
use App\Service\FileDeleter;
use Doctrine\ORM\Event\LifecycleEventArgs;


class ImageDeleteListener
{

    /**
     * @var FileDeleter
     */
    private $fileDeleter;

    public function __construct(FileDeleter $fileDeleter)
    {
        $this->fileDeleter = $fileDeleter;
    }


    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Image) {
            return;
        }

        $this->fileDeleter->remove($entity->getName());


    }


}

