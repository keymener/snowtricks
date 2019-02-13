<?php

namespace App\EventSubscriber;


use App\Entity\Mail;
use App\Entity\User;
use App\Service\MailSender;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class EmailSubscriber implements EventSubscriber
{


    /**
     * @var MailSender
     */
    private $sender;

    public function __construct(MailSender $sender)
    {

        $this->sender = $sender;
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

        ];
    }


    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();


        $this->sendEmail($entity);

    }

    public function sendEmail($entity)
    {
        if (!$entity instanceof User) {
            return;
        }

        $email = new Mail();
        $email->setDestEmail($entity->getEmail())
            ->setSubject('test')
            ->setMessage('test message');

        $this->sender->send($email);

    }
}
