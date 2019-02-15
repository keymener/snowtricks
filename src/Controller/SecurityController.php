<?php
/**
 * Created by PhpStorm.
 * User: keyme
 * Date: 15/02/2019
 * Time: 22:11
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\UserRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
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
     * @Route("/login", name="user_login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {

        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();

        $form = $this->createForm(UserRegistrationType::class);


        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'lastUsername' => $lastUsername,
            'error' => $error,
        ]);

    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

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


}