<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
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
     * New image
     * @Route("admin/image/new/trick-{id}", name="image_new", methods={"GET|POST"})
     * @param Trick $trick
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Trick $trick, Request $request)
    {

        $image = new Image();

        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image->setDateUpdate(new \DateTime());
            $image->setTrick($trick);
            $this->em->persist($image);
            $this->em->flush();

            $this->addFlash('success', "L' image a bien été ajoutée");
            return $this->redirectToRoute('trick_edit', [
                'id' => $trick->getId(),
            ]);

        }

        return $this->render('image/new.html.twig', [

            'form' => $form->createView(),

        ]);
    }


    /**
     * Edit image
     * @Route("admin/image/edit/{id}", name="image_edit", methods={"GET|POST"})
     * @param Image $image
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Image $image, Request $request)
    {
        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image->setDateUpdate(new \DateTime());

            $this->em->flush();

            $this->addFlash('success', "L' image a bien été modifiée");
            return $this->redirectToRoute('trick_edit', [
                'id' => $image->getTrick()->getId(),
            ]);

        }

        return $this->render('image/edit.html.twig', [

            'form' => $form->createView(),
            'image' => $image

        ]);
    }

    /**
     * @Route("/admin/image-delete/{id}", name="image_delete", methods="DELETE")
     * @param Request $request
     * @param Image $image
     */
    public function deleteImage(Request $request, Image $image)
    {
        if ($this->isCsrfTokenValid('delete-image', $request->request->get('_token'))) {

            $image->getTrick()->setDateUpdate(new \DateTime());
            $this->em->remove($image);
            $this->em->flush();


            $this->addFlash('success', "L'image a bien été supprimée");
        }

        return $this->redirectToRoute('trick_edit', [
            'id' => $image->getTrick()->getId(),
        ]);
    }


}