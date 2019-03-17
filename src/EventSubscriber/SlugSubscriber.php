<?php

namespace App\EventSubscriber;

use App\Entity\Trick;
use App\Service\SlugTransformer;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class SlugSubscriber implements EventSubscriber
{


    /**
     * @var SlugTransformer
     */
    private $slugTransformer;

    public function __construct(SlugTransformer $slugTransformer)
    {

        $this->slugTransformer = $slugTransformer;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }


    /**
     * Do something on prePersist events
     * @param LifecycleEventArgs $arg
     */
    public function prePersist(LifecycleEventArgs $arg)
    {
        $this->setSlug($arg);
    }

    /**
     * Do something on preUpdate events
     * @param LifecycleEventArgs $arg
     */
    public function preUpdate(LifecycleEventArgs $arg)
    {
        $this->setSlug($arg);
    }

    /**
     * Set slug on trick
     * @param LifecycleEventArgs $arg
     */
    private function setSlug(LifecycleEventArgs $arg)
    {
        $entity = $arg->getEntity();

        if (!$entity instanceof Trick) {
            return;
        }

        /** @var Trick $trick */
        $trick = $entity;

//        Get trick name
        $trickName = $trick->getName();

//        Transform trick name into slug
        $slug = $this->slugTransformer->transform($trickName);

//        Add slug to trick
        $trick->setSlug($slug);
    }


}
