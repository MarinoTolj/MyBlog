<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use App\Entity\Comments;
use App\Entity\Users;
use App\Form\BlogPostType;
use App\Form\CommentsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
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
    public function showBlogPost(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $blogPost = $entityManager->getRepository(BlogPosts::class)->find($id);
        $comments = $entityManager->getRepository(Comments::class)->findBy(['postId'=>$id]);

        $newComment=new Comments();

        $form=$this->createForm(CommentsType::class, $newComment);
        $form->handleRequest($request);

        $user = $entityManager->getRepository(Users::class)->findBy(['id'=>14])[0];

        if (!$blogPost) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        if($form->isSubmitted() && $form->isValid()){
            $newComment->setPostId($blogPost);
            $newComment->setUserId($user);

            $entityManager->persist($newComment);
            $entityManager->flush();
        }


        return $this->render('blog_posts/post.html.twig', [
            'post'=>$blogPost,
            'form'=>$form->createView(),
            'comments'=>$comments
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
