<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\VideoType;
use App\Service\TrickBySlugFinder;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
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
     * @Route("/admin/video-delete/{id}", name="video_delete", methods="DELETE")
     * @param Request $request
     * @param Video $video
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteImage(Request $request, Video $video)
    {
        if ($this->isCsrfTokenValid('delete-video', $request->request->get('_token'))) {
            $this->entityManager->remove($video);
            $this->entityManager->flush();

            $this->addFlash('success', "La video a bien été supprimée");
        }

        return $this->redirectToRoute('trick_edit', [
            'id' => $video->getTrick()->getId(),
        ]);
    }


    /**
     * Edit video
     * @Route("admin/video/edit/{id}", name="video_edit", methods={"GET|POST"})
     * @param Video $video
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Video $video, Request $request)
    {
        $form = $this->createForm(VideoType::class, $video);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $this->entityManager->flush();

            $this->addFlash('success', "La video a bien été modifiée");
            return $this->redirectToRoute('trick_edit', [
                'id' => $video->getTrick()->getId(),
            ]);

        }

        return $this->render('video/edit.html.twig', [

            'form' => $form->createView(),

        ]);
    }

    /**
     * New video
     * @Route("admin/video/new/trick/{slug}", name="video_new", methods={"GET|POST"})
     * @param string $slug
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(string $slug, Request $request, TrickBySlugFinder $trickFinder)
    {

        $trick = $trickFinder->find($slug);

        $video = new Video();

        $form = $this->createForm(VideoType::class, $video);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trick->addVideo($video);
            $this->entityManager->persist($video);
            $this->entityManager->flush();

            $this->addFlash('success', "La video a bien été ajoutée");
            return $this->redirectToRoute('trick_edit', [
                'id' => $video->getTrick()->getId(),
            ]);

        }

        return $this->render('video/new.html.twig', [

            'form' => $form->createView(),

        ]);
    }
}
