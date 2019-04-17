<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class UserController extends AbstractController
{

    /**
     * Edit a user
     *
     * @Route("/admin/user/edit", name="user_edit" , methods={"GET|POST"} )
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var User $user */
        $user = $this->getUser();

        $currentImage = $user->getImage();

        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $image = $user->getImage();
            $image->setDateUpdate(new \DateTime());

            $entityManager->flush();

            $this->addFlash('success',
                'Les modifications sur l\'utilisateur ont bien été apportées');
            return $this->redirectToRoute('trick_home');

        }


        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}