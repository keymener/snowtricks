<?php
/**
 * Created by PhpStorm.
 * User: keyme
 * Date: 15/02/2019
 * Time: 22:11
 */

namespace App\Controller;


use App\Entity\Token;
use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Form\UserRegistrationType;
use App\Service\MailSender;
use App\Service\TokenGenerator;
use App\Service\TokenSaver;
use App\Service\UrlMaker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;
    /**
     * @var MailSender
     */
    private $mailSender;
    /**
     * @var UrlMaker
     */
    private $urlMaker;
    /**
     * @var TokenSaver
     */
    private $tokenSaver;

    public function __construct(
        EntityManagerInterface $entityManager,
        TokenGenerator $tokenGenerator,
        MailSender $mailSender,
        UrlMaker $urlMaker,
        TokenSaver $tokenSaver
    )
    {
        $this->entityManager = $entityManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailSender = $mailSender;
        $this->urlMaker = $urlMaker;
        $this->tokenSaver = $tokenSaver;
    }


    /**
     * Register a user
     * @Route("/register", name="security_register", methods={"GET|POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $user = new User();

        $form = $this->createForm(UserRegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //encode password
            $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());

            //set encoded password
            $user->setPassword($encodedPassword);

            //generate token
            $token = $this->tokenGenerator->generate();

            //save Token
            $this->tokenSaver->save($user, $token);

            //generate the url for the user
            $url = $this->urlMaker->generate('security_confirm_registration', $token);

            //send the mail
            $this->mailSender->send($user, 'email/registration.html.twig', $url);

            $this->addFlash('success', "Un email vient d'être envoyé, veuillez suivre les instructions de cet email afin de confirmer l'inscription.");
            return $this->redirectToRoute('trick_home');

        }


        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/confirm_registration/{token}", name="security_confirm_registration", methods={"GET|POST"})
     * @param string $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmRegistration(string $token)
    {
        //get the user using the received token
        $tokenEntity = $this->entityManager->getRepository(Token::class)->findOneBy([
            'value' => $token
        ]);

        //make datetime comparison between current datetime and tokens datetime

        $currentDatetime = new \DateTime();

        if ($tokenEntity === null || $currentDatetime > $tokenEntity->getEndDate()) {

            //remove tokenEntity when datetime interval is over
            $this->entityManager->remove($tokenEntity);

            $this->addFlash('danger', "Le token est invalide.");

            return $this->redirectToRoute('trick_home');
        }

        $user = $tokenEntity->getUser();

        //active user
        $user->setActive(true);

        //remove tokenEntity
        $this->entityManager->remove($tokenEntity);


        $this->entityManager->flush();
        $this->addFlash('success', "Votre compte a bien été enregistré.");

        return $this->redirectToRoute('trick_home');

    }

    /**
     * Security login for user
     * @Route("/login", name="security_login", methods={"GET|POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    /**
     * Handle forgot password
     * @Route("/forgot_password", name="security_forgot", methods={"GET|POST"})
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function forgot(Request $request, MailSender $mailSender, UrlMaker $urlMaker)
    {
        $potentialUser = new User();
        $form = $this->createForm(ForgotPasswordType::class, $potentialUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->entityManager->getRepository(User::class)->findOneBy([
                'username' => $potentialUser->getUsername()
            ]);

            if ($user === null) {

                $this->addFlash('danger', "Le pseudo n'existe pas.");
                return $this->redirectToRoute('security_forgot');

            } else {

                //generate token
                $token = $this->tokenGenerator->generate();

                //save token
                $this->tokenSaver->save($user, $token);

                //generate the url
                $url = $urlMaker->generate('security_reset', $token);

                //send email
                $mailSender->send($user, 'email/forgot.html.twig', $url);


                $this->addFlash('success',
                    "Un email vient d'être envoyé, veuillez suivre les instructions de cet email pour réinitialiser votre mot de passe.");

            }


        }

        return $this->render('security/forgot.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Reset password
     * @Route("/reset_password/{token}", name="security_reset", methods={"GET|POST"})
     * @param Request $request
     * @param string $token
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function reset(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        $tokenEntity = $this->entityManager->getRepository(Token::class)->findOneBy([
                'value' => $token
            ]
        );


        //compare current date and the tokens date
        $currentDatetime = new \DateTime();

        if ($tokenEntity === null || $currentDatetime > $tokenEntity->getEndDate()) {

            $this->addFlash('danger', "Le token est invalide.");

            return $this->redirectToRoute('trick_home');
        }
        $user = $tokenEntity->getUser();

        $formUser = new User();


        $form = $this->createForm(ResetPasswordType::class, $formUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //check if email set is not the same than the real user email
            if ($formUser->getEmail() !== $user->getEmail()) {

                $this->addFlash('danger', "l'email n'est pas valide.");
                return $this->redirectToRoute('trick_home');
            }

            //remove tokenEntity
            $this->entityManager->remove($tokenEntity);



            $user->setActive(true);

            //set new password
            $user->setPassword($passwordEncoder->encodePassword($user, $formUser->getPassword()));
            $this->entityManager->flush();

            $this->addFlash('success', "Mot de passe mis à jour");
            return $this->redirectToRoute('trick_home');

        }

        return $this->render('security/reset.html.twig', [
            'form' => $form->createView()
        ]);

    }


    /**
     * Security logout
     * @Route("/logout", name="security_logout", methods={"GET|POST"})
     */
    public function logout()
    {

    }


}
