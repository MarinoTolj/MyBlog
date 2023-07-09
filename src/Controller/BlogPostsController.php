<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogPostsController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager): Response
    {

        return $this->render('blog_posts/index.html.twig', [
            'controller_name' => 'BlogPostsController',
        ]);
    }
    public function showBlogPost(EntityManagerInterface $entityManager, int $id): Response
    {
        $blogPost = $entityManager->getRepository(BlogPosts::class)->find($id);

        if (!$blogPost) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('blog_posts/index.html.twig', [
            'controller_name' => 'BlogPostsController',
            'title'=>$blogPost->getTitle(),
            'body'=>$blogPost->getBody(),
        ]);

    }
    public function createBlogPost(EntityManagerInterface $entityManager): Response
    {
        $blogPost = new BlogPosts();
        $blogPost->setBody('Keyboard');
        $blogPost->setTitle(1999);

        $entityManager->persist($blogPost);

        $entityManager->flush();

        return $this->render('blog_posts/index.html.twig', [
            'controller_name' => 'BlogPostsController',
        ]);
    }
}
