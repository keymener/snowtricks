<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Video;
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
}
