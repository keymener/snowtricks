<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Form\ImageType;
use App\Service\SlugTransformer;
use App\Service\TrickBySlugFinder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{


    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * New image
     * @Route("admin/image/new/trick-{id}/{slug}", name="image_new", methods={"GET|POST"})
     * @param string $slug
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Trick $trick, string $slug, Request $request, SlugTransformer $slugTransformer)
    {

        if ($trick->getSlug() !== $slug) {
            return $this->redirectToRoute('image_new', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug()
            ]);
        }

        $image = new Image();

        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image->setDateUpdate(new \DateTime());
            $image->setTrick($trick);
            $this->entityManager->persist($image);
            $this->entityManager->flush();

            $this->addFlash('success', "L' image a bien été ajoutée");
            return $this->redirectToRoute('trick_edit', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug()
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

            $this->entityManager->flush();

            $this->addFlash('success', "L' image a bien été modifiée");
            return $this->redirectToRoute('trick_edit', [
                'id' => $image->getTrick()->getId(),
                'slug' => $image->getTrick()->getSlug()
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
            $this->entityManager->remove($image);
            $this->entityManager->flush();


            $this->addFlash('success', "L'image a bien été supprimée");
        }

        return $this->redirectToRoute('trick_edit', [
            'id' => $image->getTrick()->getId(),
            'slug' => $image->getTrick()->getSlug()
        ]);
    }


}
