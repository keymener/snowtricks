<?php

namespace App\EventSubscriber;


use App\Entity\Mail;
use App\Entity\User;
use App\Service\MailSender;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailSubscriber extends AbstractController implements EventSubscriber
{


    /**
     * @var MailSender
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {

        $this->mailer = $mailer;
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

    /**
     * User registration email
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $message = new \Swift_Message();
        $message->setFrom('snowtrick@test.com')
            ->setTo($entity->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'email/registration.html.twig',
                    ['name' => $entity->getUsername()]
                ),
                'text/html'

            );

        $this->mailer->send($message);

        return;


    }

}


