<?php


namespace App\Service;


use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TrickBySlugFinder
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(string $slug)
    {


        $trick = $this->entityManager->getRepository(Trick::class)->findOneBy([
                'slug' => $slug
            ]
        );


        if (!$trick) {

            throw new NotFoundHttpException("Trick does not exist");
        }

        return $trick;
    }
}