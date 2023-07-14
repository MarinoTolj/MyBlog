<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class HomePageController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $blogPosts = $entityManager->getRepository(BlogPosts::class)->findAll();


        //query to get all blog posts
        $qb = $entityManager->createQueryBuilder('a')
            ->select("a")
            ->from("App:BlogPosts", "a");

        $query = $qb->getQuery();


        //paginate query with KnpPaginator
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('homePage/index.html.twig', [
            'blogPosts' => $blogPosts,
            'pagination' => $pagination
        ]);
    }
}
