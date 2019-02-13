<?php


namespace App\Service;


use App\Entity\Mail;

class MailSender
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    private $fromEmail;

    public function __construct(\Swift_Mailer $mailer, $fromEmail)
    {

        $this->mailer = $mailer;
        $this->fromEmail = $fromEmail;
    }

    /**
     * @param Mail $mail
     */
    public function send(Mail $mail)
    {


    }

}