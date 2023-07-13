<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use App\Entity\Comments;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomePageController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $blogPosts = $entityManager->getRepository(BlogPosts::class)->findAll();


        $qb = $entityManager->createQueryBuilder('a')
            ->select("a")
            ->from("App:BlogPosts", "a");

        $query = $qb->getQuery();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        //Adminadmin1
        //Useruser123
        //~
        /*"controllers": {
    "@symfony/ux-turbo": {
      "turbo-core": {
        "enabled": true,
        "fetch": "eager"
      },
      "mercure-turbo-stream": {
        "enabled": false,
        "fetch": "eager"
      }
    }
  },*/
        //$users = $entityManager->getRepository(Users::class)->findAll();
        //$comments = $entityManager->getRepository(Comments::class)->findAll();

//        $this->removeDataFromDatabase($comments, $entityManager);
        //$this->removeDataFromDatabase($users, $entityManager);
//        $this->removeDataFromDatabase($blogPosts, $entityManager);
        return $this->render('homePage/index.html.twig', [
            'blogPosts' => $blogPosts,
            'pagination' => $pagination
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
