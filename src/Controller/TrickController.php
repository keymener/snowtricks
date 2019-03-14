<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickEditType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Security\Voter\TrickVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{

    private $em;

    const TRICKS_PER_PAGE = 15;
    const COMMENTS_PER_PAGE = 5;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

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
     * @Route("/trick/new", name="trick_new")
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

            $this->em->persist($trick);
            $this->em->flush();


            $this->addFlash('success', 'La figure a bien été enregistrée');

            return $this->redirectToRoute('trick_home');

        }

        return $this->render('trick/newTrick.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * Show a trick
     * @Route("/trick/{id}/{moreComments}", defaults={"more"=null}, name="trick_view", methods={"GET|POST"})
     * @param Trick $trick
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view(Trick $trick, string $moreComments = null)
    {

        //comment form
        $form = $this->createForm(CommentType::class);

        //get firsts comments if more is not set
        if (null === $moreComments) {
            $comments = $this->em->getRepository(Comment::class)->findCommentsByTrick(
                $trick,
                self::COMMENTS_PER_PAGE
            );
        } else {

            //get all comments
            $comments = $this->em->getRepository(Comment::class)->findAll();
        }


        return $this->render('trick/view.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'form' => $form->createView(),
            'moreComments' => $moreComments

        ]);
    }


    /**
     * Edit a trick
     * @Route("admin/trick/edit/{id}", name="trick_edit", methods={"GET|POST"})
     * @param Trick $trick
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Trick $trick, Request $request)
    {
        if (!$this->isGranted(TrickVoter::EDIT, $trick)) {

            $this->addFlash('danger', "Vous n'avez pas d'authorisation pour modifier cette figure");
            return $this->redirectToRoute('trick_home');
        }

        $form = $this->createForm(TrickEditType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $trick->setDateUpdate(new \DateTime());

            $this->em->flush();

            $this->addFlash('success', 'La figure a bien été modifiée');


        }

        return $this->render('trick/view.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'edit' => true
        ]);
    }


    /**
     * Delete trick
     * @Route("/admin/trick-delete/{id}", name="trick_delete", methods="DELETE")
     * @param Request $request
     * @param Trick $image
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, Trick $trick)
    {

        if (!$this->isGranted(TrickVoter::EDIT, $trick)) {

            $this->addFlash('danger', "Vous n'avez pas d'authorisation pour supprimer cette figure");
            return $this->redirectToRoute('trick_home');
        }

        if ($this->isCsrfTokenValid('delete-trick', $request->request->get('_token'))) {


            $this->em->remove($trick);
            $this->em->flush();


            $this->addFlash('success', "La figure a bien été supprimée");
        }

        return $this->redirectToRoute('trick_home', [

        ]);
    }


}
