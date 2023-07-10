<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use App\Entity\Users;
use App\Form\BlogPostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class BlogPostsController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager): Response
    {

        return $this->render('blog_posts/newPost.html.twig', [
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

        return $this->render('blog_posts/post.html.twig', [
            'title'=>$blogPost->getTitle(),
            'body'=>$blogPost->getBody(),
        ]);

    }
    public function newBlogPost(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger): Response
    {
        $blogPost = new BlogPosts();
        $form=$this->createForm(BlogPostType::class, $blogPost);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //var_dump($blogPost);
            $newFilename='';
            $imageFile=$form->get("imageFilename")->getData();
            if($imageFile){
                $originalFilename=pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename=$slugger->slug($originalFilename);
                $newFilename=$safeFilename.'_'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                }catch (FileException $e){
                    var_dump($e);
                }
            }
            $blogPost->setImageFilename($newFilename);
            $entityManager->persist($blogPost);
            $entityManager->flush();

            return $this->redirectToRoute("homePage");
        }


        return $this->render('blog_posts/newPost.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
