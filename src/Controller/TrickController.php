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


            $trick->setDate(new \DateTime());

            $this->em->persist($trick);
            $this->em->flush();


            $this->addFlash('success', 'La figure a bien été enregistrée');

            return $this->redirectToRoute('trick_select_first_image', [
                'id' => $trick->getId(),
            ]);

        }

        return $this->render('trick/newTrick.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/trick-{id}/select-first-image", name="trick_select_first_image", methods={"GET|POST"})
     */
    public function selectFirstImage(Trick $trick)
    {


        return $this->render('trick/selectFirstImage.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * Update the first Image
     * @Route("/trick/image-first-{id}", name="trick_set_image_first", methods="GET|POST")
     * @param Image $image
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setFirstImage(Image $image, Request $request)
    {


        $submitedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('trick-first-image', $submitedToken)) {

            $otherFirstImage = $this->em
                ->getRepository(Image::class)
                ->findOneBy([
                    'trick' => $image->getTrick(),
                    'isFirst' => 1
                ]);


            if ($image !== $otherFirstImage && null !== $otherFirstImage) {
                $otherFirstImage->setIsFirst(false);
            }

            $image->setIsFirst(true);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'image à la une a bien été selectionné');
            return $this->redirectToRoute('home');
        }

        return $this->redirectToRoute('home');
    }

}
