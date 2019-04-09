<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickEditType;
use App\Form\TrickType;
use App\Security\Voter\TrickVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{

    private $entityManager;

    const TRICKS_PER_PAGE = 5;
    const COMMENTS_PER_PAGE = 5;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }

    /**
     * Home page
     *
     * @Route("/", name="trick_home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home()
    {
        $tricks = $this->getDoctrine()->getRepository(Trick::class)->getTricks(self::TRICKS_PER_PAGE);
        return $this->render('trick/home.html.twig', [
            'tricks' => $tricks,
            'maxResult' => self::TRICKS_PER_PAGE + self::TRICKS_PER_PAGE,

        ]);
    }

    /**
     * Show more tricks
     *
     * @Route("/view-{maxResult}", name="trick_more", methods={"GET"})
     * @param int $maxResult
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function more(int $maxResult)
    {

        if ($maxResult < self::TRICKS_PER_PAGE) {
            $maxResult = self::TRICKS_PER_PAGE;
        }

        $tricks = $this->getDoctrine()->getRepository(Trick::class)->getTricks($maxResult);


        return $this->render('trick/home.html.twig', [
            'tricks' => $tricks,
            'maxResult' => $maxResult + self::TRICKS_PER_PAGE,

        ]);

    }


    /**
     * Create a new trick
     * @Route("/member/trick/new", name="trick_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newTrick(Request $request)
    {

        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            //set the current datetime
            $trick->setDate(new \DateTime());

            //set the current user
            $trick->setUser($this->getUser());

            $this->entityManager->persist($trick);
            $this->entityManager->flush();


            $this->addFlash('success', 'La figure a bien été enregistrée');

            return $this->redirectToRoute('trick_home');

        }

        return $this->render('trick/newTrick.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * Show a trick
     * @Route("/trick/{id}/{slug}/{showButton}", name="trick_view", methods={"GET|POST"})
     * @param Trick $trick
     * @param string $slug
     * @param bool $showButton
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view(Trick $trick, string $slug, bool $showButton = false)
    {
        //if slug is not corresponding to tricks-slug, redirect to good page
        if ($trick->getSlug() !== $slug) {
            return $this->redirectToRoute('trick_view', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug(),

            ]);
        }

        //comment form
        $form = $this->createForm(CommentType::class);

        $countComments = $this->entityManager->getRepository(Comment::class)->countCommentsByTrick(
            $trick
        );

        //get firsts comments if button is false
        if ($showButton == false) {


            $comments = $this->entityManager->getRepository(Comment::class)->findCommentsByTrick(
                $trick,
                self::COMMENTS_PER_PAGE
            );

            //hide button when comments are below the limit
            if ($countComments > self::COMMENTS_PER_PAGE) {
                $showButton = true;
            }


        } else {

            //get all comments
            $comments = $this->entityManager->getRepository(Comment::class)->findBy(
                [
                    'trick' => $trick->getId()
                ],
                [
                    'id' => 'DESC'
                ]);
            $showButton = false;

        }


        return $this->render('trick/view.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'form' => $form->createView(),
            'showButton' => $showButton,
            'countComments' => $countComments

        ]);
    }


    /**
     * Edit a trick
     * @Route("admin/trick/edit/{id}/{slug}", name="trick_edit", methods={"GET|POST"})
     * @param string $slug
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Trick $trick, string $slug, Request $request)
    {

        if ($trick->getSlug() !== $slug) {
            return $this->redirectToRoute('trick_edit', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug(),

            ]);
        }

        // get Trick name to handle display of the name when a constraint error appear
        $trickName = $trick->getName();

        if (!$this->isGranted(TrickVoter::EDIT, $trick)) {

            $this->addFlash('danger', "Vous n'avez pas d'authorisation pour modifier cette figure");
            return $this->redirectToRoute('trick_home');
        }

        $form = $this->createForm(TrickEditType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $trick->setDateUpdate(new \DateTime());

            $this->entityManager->flush();

            $this->addFlash('success', 'La figure a bien été modifiée');
            return $this->redirectToRoute('trick_view', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug()
            ]);


        }


        // get Trick name to handle display of the name when a constraint error appear
        $trick->setName($trickName);

        return $this->render('trick/view.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'edit' => true,

        ]);
    }


    /**
     * Delete trick
     * @Route("/admin/trick-delete/{id}/{slug}", name="trick_delete", methods="DELETE")
     * @param Trick $trick
     * @param string $slug
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Trick $trick, string $slug, Request $request)
    {

        if ($trick->getSlug() !== $slug) {
            return $this->redirectToRoute('trick_delete', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug(),

            ]);
        }


        if (!$this->isGranted(TrickVoter::EDIT, $trick)) {

            $this->addFlash('danger', "Vous n'avez pas d'authorisation pour supprimer cette figure");
            return $this->redirectToRoute('trick_home');
        }

        if ($this->isCsrfTokenValid('delete-trick', $request->request->get('_token'))) {


            $this->entityManager->remove($trick);
            $this->entityManager->flush();


            $this->addFlash('success', "La figure a bien été supprimée");
        }

        return $this->redirectToRoute('trick_home', [

        ]);
    }


}

