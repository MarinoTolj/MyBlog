<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use App\Entity\Comments;
use App\Entity\Users;
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
        //~
        $users = $entityManager->getRepository(Users::class)->findAll();
        $comments = $entityManager->getRepository(Comments::class)->findAll();

//        $this->removeDataFromDatabase($comments, $entityManager);
        //$this->removeDataFromDatabase($users, $entityManager);
//        $this->removeDataFromDatabase($blogPosts, $entityManager);


        return $this->render('homePage/index.html.twig', [
            'blogPosts' => $blogPosts
        ]);
    }

    public function removeDataFromDatabase(mixed $data, EntityManagerInterface $entityManager)
    {
        foreach ($data as $item) {
            //if ($item->getUsername() == "admin") {
            $entityManager->remove($item);
            $entityManager->flush();
            //}
        }
    }
}
