<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
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