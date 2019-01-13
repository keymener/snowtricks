<?php

namespace App\Controller;

use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    /**
     * @Route("/image/new", name="new_image")
     */
    public function newImage(Request $request)
    {

        $image = new Image();

        $form = $this->createForm('App\Form\ImageType', $image);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            dump($image);

            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            $this->addFlash('success', 'L\'image a bien été ajoutée' );
            $this->redirectToRoute('new_image');


        }

        return $this->render('image/newImage.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
