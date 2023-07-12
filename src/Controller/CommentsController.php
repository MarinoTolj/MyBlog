<?php

namespace App\Controller;

use App\Entity\Comments;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    #[Route('/comments', name: 'app_comments')]
    public function index(): Response
    {
        return $this->render('comments/index.html.twig', [
            'controller_name' => 'CommentsController',
        ]);
    }

    public function deleteComment(EntityManagerInterface $entityManager, int $id): Response
    {
        $comment = $entityManager->getRepository(Comments::class)->findBy(['id' => $id]);

        $entityManager->remove($comment[0]);
        $entityManager->flush();
        return new Response("All good");
    }

    public function commentWithUser(EntityManagerInterface $entityManager, int $id): Response
    {
        $comment = $entityManager->getRepository(Comments::class)->findBy(['id' => $id])[0];
        $user = $entityManager->getRepository(Users::class)->findBy(['id' => $comment->getUserId()])[0];

        return $this->render("comments/_commentWithUser.html.twig", ['comment' => $comment, 'user' => $user]);
    }
}
