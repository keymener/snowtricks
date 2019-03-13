<?php


namespace App\Service;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;


class MailSender
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $templating;
    private $fromEmail;


    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, $fromEmail)
    {

        $this->mailer = $mailer;

        $this->templating = $templating;

        $this->fromEmail = $fromEmail;
    }

    /**
     * Send an email
     * @param User $destUser
     * @param $view
     * @param $param
     */
    public function send(User $destUser, $view, $param)
    {


        $message = new \Swift_Message();
        $message->setFrom($this->fromEmail)
            ->setTo($destUser->getEmail())
            ->setBody(
                $this->templating->render(
                    $view, [
                        'user' => $destUser,
                        'param' => $param
                    ]
                ),
                'text/html'

            );

        $this->mailer->send($message);
        return;

    }

}