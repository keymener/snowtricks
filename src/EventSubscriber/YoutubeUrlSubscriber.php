<?php
/**
 * Created by PhpStorm.
 * User: keyme
 * Date: 24/03/2019
 * Time: 23:39
 */

namespace App\EventSubscriber;


use App\Entity\Video;
use App\Service\YoutubeLinkConvertor;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class YoutubeUrlSubscriber implements EventSubscriber
{


    /**
     * @var YoutubeLinkConvertor
     */
    private $youtubeLinkConvertor;

    public function __construct(YoutubeLinkConvertor $youtubeLinkConvertor)
    {
        $this->youtubeLinkConvertor = $youtubeLinkConvertor;
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
        $this->setUrl($arg);
    }

    /**
     * Do something on preUpdate events
     * @param LifecycleEventArgs $arg
     */
    public function preUpdate(LifecycleEventArgs $arg)
    {
        $this->setUrl($arg);
    }

    public function setUrl(LifecycleEventArgs $arg)
    {
        $entity = $arg->getEntity();

        if (!$entity instanceof Video) {
            return;
        }

        /** @var Video $video */
        $video = $entity;

        //convert url to embed
        $url = $this->youtubeLinkConvertor->convert($video->getUrl());


        $video->setUrl($url);


    }


}
