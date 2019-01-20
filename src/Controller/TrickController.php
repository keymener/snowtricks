<?php

namespace App\Controller;

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

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/trick/new", name="trick_new")
     */
    public function newTrick(Request $request, FileUploader $fileUploader)
    {

        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $trick->getImage();
            $fileName = $fileUploader->upload($file);

            $trick->setImage($fileName);

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
