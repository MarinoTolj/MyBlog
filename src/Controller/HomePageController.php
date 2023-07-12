<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomePageController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager): Response
    {
        $blogPosts = $entityManager->getRepository(BlogPosts::class)->findAll();
        //Adminadmin1
        //Useruser123
//        $users = $entityManager->getRepository(Users::class)->findAll();
//        $comments = $entityManager->getRepository(Comments::class)->findAll();
//
//        foreach ($comments as $comment){
//                    $entityManager->remove($comment);
//                    $entityManager->flush();
//                }
//        foreach ($users as $user){
//            $entityManager->remove($user);
//            $entityManager->flush();
//        }


        return $this->render('homePage/index.html.twig', [
            'blogPosts' => $blogPosts
        ]);
    }
}
