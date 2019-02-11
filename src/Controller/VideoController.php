<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\VideoType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
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
     * @Route("/admin/video-delete/{id}", name="video_delete", methods="DELETE")
     * @param Request $request
     * @param Video $video
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteImage(Request $request, Video $video)
    {
        if ($this->isCsrfTokenValid('delete-video', $request->request->get('_token'))) {
            $this->em->remove($video);
            $this->em->flush();

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


            $this->em->flush();

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
     * @Route("admin/video/new/{id}", name="video_new", methods={"GET|POST"})
     * @param Trick $trick
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Trick $trick, Request $request)
    {

        $video = new Video();

        $form = $this->createForm(VideoType::class, $video);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trick->addVideo($video);
            $this->em->persist($video);
            $this->em->flush();

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
