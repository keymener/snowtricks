<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

    const COMMENT_PER_PAGE = 10;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {

        $this->em = $em;
    }

    /**
     * Add new comment
     *
     * @Route("comment/new/{id}", name="comment_new", methods={"GET|POST"})
     * @param Trick $trick
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Trick $trick, Request $request)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $comment = new Comment();


        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setTrick($trick);
            $comment->setUser($this->getUser());

            $this->em->persist($comment);
            $this->em->flush();

            $this->addFlash('success', "Votre message a bien été enregistré");
            return $this->redirectToRoute('trick_view', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug(),
            ]);

        }


        return $this->render('trick/view.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);


    }

}

