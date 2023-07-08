<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager): Response
    {
        $blogPosts = $entityManager->getRepository(BlogPosts::class)->findAll();

        if (!$blogPosts) {
            throw $this->createNotFoundException(
                'No blog posts found'
            );
        }

        return $this->render('homePage/index.html.twig', [
            'controller_name' => 'HomePage',
            'blogPosts'=>$blogPosts
        ]);
    }
}
