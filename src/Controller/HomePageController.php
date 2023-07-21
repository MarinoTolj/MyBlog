<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use App\Form\BlogPostType;
use App\Form\FilterType;
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
        $filterData = "";
        $filterForm = $this->createForm(FilterType::class);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $filterData = $filterForm->get('title')->getData();

        }
        $response = new Response(null, $filterForm->isSubmitted() ? 422 : 200);

        $qb = $entityManager->createQueryBuilder('a');
        $qb->select("a")
            ->from("App:BlogPosts", "a")
            ->setParameter('filterData', '%' . $filterData . '%')
            ->where($qb->expr()->like('a.title', ':filterData'));

        $query = $qb->getQuery();


        //paginate query with KnpPaginator
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('homePage/index.html.twig', [
            'blogPosts' => $blogPosts,
            'pagination' => $pagination,
            'form' => $filterForm->createView()
        ], $response);
    }
}
