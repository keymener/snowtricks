<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickEditType;
use App\Form\TrickType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{

    private $em;

    const TRICKS_PER_PAGE = 15;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

    }

    /**
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
     * @Route("/view-{maxResult}", name="trick_more", methods={"GET"})
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
     * @Route("/trick/new", name="trick_new")
     */
    public function newTrick(Request $request)
    {

        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            //set the current datetime
            $trick->setDate(new \DateTime());

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
     * @Route("/trick/{id}", name="trick_view", methods={"GET"})
     * @param Trick $trick
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view(Trick $trick)
    {
        return $this->render('trick/view.html.twig', [
            'trick' => $trick

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
    public function deleteImage(Request $request, Trick $trick)
    {
        if ($this->isCsrfTokenValid('delete-trick', $request->request->get('_token'))) {


            $this->em->remove($trick);
            $this->em->flush();


            $this->addFlash('success', "La figure a bien été supprimée");
        }

        return $this->redirectToRoute('trick_home', [

        ]);
    }


}
