<?php

namespace App\Controller;

use App\Entity\Comments;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class CommentsController extends AbstractController
{

    public function deleteComment(EntityManagerInterface $entityManager, int $id): Response
    {
        if (!$this->isGranted('ROLE_USER') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Not Allowed');
        }

        $comment = $entityManager->getRepository(Comments::class)->findBy(['id' => $id]);

        if (!$comment) {
            throw $this->createNotFoundException('No comment found for id ' . $id);
        }

        $entityManager->remove($comment[0]);
        $entityManager->flush();
        return new Response("Deleted comment with id: " . $id);
    }

    /**
     * Used inside twig to render each comment with appropriate user.
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     *
     */
    public function commentWithUser(EntityManagerInterface $entityManager, int $id): Response
    {
        $comment = $entityManager->getRepository(Comments::class)->findBy(['id' => $id]);
        if (!$comment) {
            throw $this->createNotFoundException('No comment found for id ' . $id);
        }

        $user = $entityManager->getRepository(Users::class)->findBy(['id' => $comment[0]->getUserId()]);
        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $comment[0]->getUserId());
        }

        return $this->render("comments/_commentWithUser.html.twig", ['comment' => $comment[0], 'user' => $user[0]]);
    }
}
