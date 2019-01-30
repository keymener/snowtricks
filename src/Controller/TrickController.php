<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{

    private $em;

    const TRICKS_PER_PAGE = 3;

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


            $trick->setDate(new \DateTime());

            $this->em->persist($trick);
            $this->em->flush();


            $this->addFlash('success', 'La figure a bien été enregistrée');

            return $this->redirectToRoute('home');

        }

        return $this->render('trick/newTrick.html.twig', [
            'form' => $form->createView(),

        ]);
    }



}
