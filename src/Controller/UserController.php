<?php

namespace App\Controller;

use App\Entity\PostCategories;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $categories=$entityManager->getRepository(PostCategories::class)->findAll();
        $newCategory=new PostCategories();
        $addCategoryForm=$this->createForm(CategoryType::class, $newCategory);

        $addCategoryForm->handleRequest($request);

        if($addCategoryForm->isSubmitted() && $addCategoryForm->isValid()){
            $entityManager->persist($newCategory);
            $entityManager->flush();
        }

        return $this->render('user/index.html.twig', ['categories'=>$categories, 'form'=>$addCategoryForm->createView()]);
    }

//    public function editCategory(EntityManagerInterface $entityManager, int $id): Response
//    {
//        $category=$entityManager->getRepository(PostCategories::class)->findBy(['id'=>$id]);
//
//        retr
//
//    }

    public function editProfile($userId):Response
    {
        return $this->render('user/editProfile.html.twig', );
    }
}
