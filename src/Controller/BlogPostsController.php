<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use App\Entity\Comments;
use App\Entity\PostCategories;
use App\Form\BlogPostType;
use App\Form\CommentsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;


class BlogPostsController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function editBlogPost(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger, int $id): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $locale = $request->getLocale();
        $currentBlogPost = $entityManager->getRepository(BlogPosts::class)->findOneBy(['id' => $id, 'locale' => $locale]);


        if (!$currentBlogPost) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $newFilename = $currentBlogPost->getImageFilename();

        $editForm = $this->createForm(BlogPostType::class, $currentBlogPost, [
            'entity_manager' => $entityManager,
        ]);
        $editForm->get('locale')->setData($locale);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $imageFile = $editForm->get("imageFilename")->getData();

            //if admin chose new image file, upload it and delete old one
            if ($imageFile) {

                $filesystem = new Filesystem();
                //delete old image file
                $projectDir = $this->getParameter('kernel.project_dir');
                $filesystem->remove($projectDir . "/public/uploads/images/" . $newFilename);

                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '_' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    var_dump($e);
                }
            }

            $categories = $editForm->get("categories")->getData();
            foreach ($categories as $key => $value) {
                $category = $entityManager->getRepository(PostCategories::class)->findBy(['name' => $value]);
                $currentBlogPost->addPostCategory($category[0]);
            }
            $currentBlogPost->setImageFilename($newFilename);
            $locale = $editForm->get("locale")->getData();
            $currentBlogPost->setLocale($locale);

            $entityManager->persist($currentBlogPost);
            $entityManager->flush();
            return $this->redirectToRoute('show_blog_post', ['id' => $currentBlogPost->getId(), '_locale' => $locale]);
        }

        $response = new Response(null, $editForm->isSubmitted() ? 422 : 200);


        return $this->render("blog_posts/edit.html.twig", [
            'form' => $editForm->createView(),
            'blogPostId' => $currentBlogPost->getId(),
        ], $response);
    }


    public function showBlogPost(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $comments = $entityManager->getRepository(Comments::class)->findBy(['postId' => $id]);

        $locale = $request->getLocale();
        $blogPost = $entityManager->getRepository(BlogPosts::class)->findOneBy(['id' => $id, 'locale' => $locale]);

        if (!$blogPost) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $newComment = new Comments();

        $form = $this->createForm(CommentsType::class, $newComment);
        $form->handleRequest($request);

        $user = $this->getUser();


        if ($form->isSubmitted() && $form->isValid()) {
            $newComment->setPostId($blogPost);
            $newComment->setUserId($user);

            $entityManager->persist($newComment);
            $entityManager->flush();

            return $this->redirectToRoute('show_blog_post', ['id' => $id]);

        }


        return $this->render('blog_posts/post.html.twig', [
            'post' => $blogPost,
            'form' => $form->createView(),
            'comments' => $comments
        ]);

    }

    public function newBlogPost(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $blogPost = new BlogPosts();

        $form = $this->createForm(BlogPostType::class, $blogPost, ['entity_manager' => $entityManager]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $newFilename = '';
            $imageFile = $form->get("imageFilename")->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '_' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    var_dump($e);
                }
            }
            $categories = $form->get("categories")->getData();
            $locale = $form->get("locale")->getData();
            $blogPost->setImageFilename($newFilename);
            $blogPost->setLocale($locale);
            foreach ($categories as $key => $value) {
                $category = $entityManager->getRepository(PostCategories::class)->findBy(['name' => $value]);
                $blogPost->addPostCategory($category[0]);
            }

            $entityManager->persist($blogPost);
            $entityManager->flush();

            return $this->redirectToRoute("show_blog_post", ['id' => $blogPost->getId()]);
        }

        $response = new Response(null, $form->isSubmitted() ? 422 : 200);


        return $this->render('blog_posts/newPost.html.twig', [
            'form' => $form->createView(),
        ], $response);
    }


    public function upvoteBlogPost(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {

        $blogPost = $entityManager->getRepository(BlogPosts::class)->find($id);
        $user = $this->security->getUser();

        $blogPost->addLikedByUser($user);

        $entityManager->persist($blogPost);
        $entityManager->flush();

        return new Response("All good");
    }

    public function downvoteBlogPost(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {

        $blogPost = $entityManager->getRepository(BlogPosts::class)->find($id);
        $user = $this->security->getUser();

        $blogPost->removeLikedByUser($user);

        $entityManager->persist($blogPost);
        $entityManager->flush();

        return new Response("All good");
    }

    public function favoriteBlogPost(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {

        $blogPost = $entityManager->getRepository(BlogPosts::class)->find($id);
        $user = $this->security->getUser();
        $blogPost->addFavoritedByUser($user);

        $entityManager->persist($blogPost);
        $entityManager->flush();

        return $this->redirectToRoute('show_blog_post', ['id' => $blogPost->getId()]);
    }

    public function unfavoriteBlogPost(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {

        $blogPost = $entityManager->getRepository(BlogPosts::class)->find($id);
        $user = $this->security->getUser();
        $blogPost->removeFavoritedByUser($user);

        $entityManager->persist($blogPost);
        $entityManager->flush();

        return $this->redirectToRoute('show_blog_post', ['id' => $blogPost->getId()]);
    }

    public function deleteBlogPost(EntityManagerInterface $entityManager, int $id): Response
    {


        $currentBlogPost = $entityManager->getRepository(BlogPosts::class)->findBy(['id' => $id]);

        if (!$currentBlogPost) {
            throw $this->createNotFoundException(
                'No post found for id:' . $id
            );
        }
        $currentBlogPost = $currentBlogPost[0];

        $blogPostComments = $entityManager->getRepository(Comments::class)->findBy(['postId' => $currentBlogPost->getId()]);
        //when deleting blog post make sure all of it comments are first deleted because they share a relationship
        foreach ($blogPostComments as $comment) {
            $entityManager->remove($comment);
        }

        $entityManager->remove($currentBlogPost);
        $entityManager->flush();

        return new Response("Deleted post with id:" . $id);
    }


}
