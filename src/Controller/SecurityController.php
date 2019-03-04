<?php
/**
 * Created by PhpStorm.
 * User: keyme
 * Date: 15/02/2019
 * Time: 22:11
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Form\UserRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * Register a user
     * @Route("/register", name="security_register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $user = new User();

        $form = $this->createForm(UserRegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());

            $user->setPassword($encodedPassword);


            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', "L'enregistrement a bien été effectué");
            return $this->redirectToRoute('trick_home');

        }


        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/login", name="security_login")
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
     * @Route("/forgot_password", name="security_forgot")
     */
    public function forgot(Request $request, \Swift_Mailer $mailer)
    {
        $potentialUser = new User();
        $form = $this->createForm(ForgotPasswordType::class, $potentialUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->em->getRepository(User::class)->findOneBy([
                'username' => $potentialUser->getUsername()
            ]);

            if ($user === null) {

                $this->addFlash('danger', "Le pseudo n'existe pas.");
                return $this->redirectToRoute('security_forgot');

            } else {

                $token = sha1(random_bytes(10));
                $user->setResetToken($token);
                $this->em->flush();

                $url = $this->generateUrl('security_reset', [
                    'token' => $token,
                ], UrlGeneratorInterface::ABSOLUTE_URL);


                $message = new \Swift_Message();
                $message->setFrom('snowtrick@test.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'email/forgot.html.twig', [
                                'user' => $user,
                                'url' => $url

                            ]
                        ),
                        'text/html'

                    );

                $mailer->send($message);

                $this->addFlash('success',
                    "Un email vient d'être envoyé, veuillez suivre les instruction de cet email pour réinitialiser votre mot de passe.");

            }


        }

        return $this->render('security/forgot.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset_password/{token}", name="security_reset")
     */
    public function reset(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->em->getRepository(User::class)->findOneBy([
                'resetToken' => $token
            ]
        );

        if ($user === null) {

            $this->addFlash('danger', "Le token est inconnu.");

            return $this->redirectToRoute('trick_home');
        }

        $formUser = new User();

        $form = $this->createForm(ResetPasswordType::class, $formUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if ($formUser->getEmail() ==! $user->getEmail()) {

                $this->addFlash('danger', "l'email n'est pas valide.");
                return $this->redirectToRoute('trick_home');
            }

                $user->setResetToken(null);
                $user->setPassword($passwordEncoder->encodePassword($user, $formUser->getPassword()));
                $this->em->flush();

                $this->addFlash('success', "Mot de passe mis à jour");
                return $this->redirectToRoute('trick_home');

        }

        return $this->render('security/reset.html.twig', [
            'form' => $form->createView()
        ]);

    }


    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {

    }


}