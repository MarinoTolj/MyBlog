<?php

namespace App\DataFixtures;

use App\DataFixtures\ORM\AppNativeLoader;
use App\Entity\BlogPosts;
use App\Entity\Comments;
use App\Entity\PostCategories;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Throwable\LoadingThrowable;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager)
    {
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws LoadingThrowable
     */
    public function load(ObjectManager $manager)
    {
        $loader = new AppNativeLoader();
        $blogPosts = $loader->loadFile(__DIR__ . '/fixtures/blogPostsFixtures.yaml')->getObjects();
        $users = $loader->loadFile(__DIR__ . '/fixtures/usersFixtures.yaml')->getObjects();
        $comments = $loader->loadFile(__DIR__ . '/fixtures/commentsFixtures.yaml')->getObjects();
        $categories = $loader->loadFile(__DIR__ . '/fixtures/categoriesFixtures.yaml')->getObjects();

        $blogPostsArray = [];
        $usersArray = [];
        $categoriesArray = [];


        $admin = new Users();
        $admin->setUsername("Admin");
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setEmail("admin@admin");
        $password = $this->hasher->hashPassword($admin, "Adminadmin1");
        $admin->setPassword($password);
        $admin->setAvatar("man_64b3e66cc7a49.png");
        $manager->persist($admin);

        foreach ($users as $user) {
            $user->setPassword($this->hasher->hashPassword($user, 'Useruser123'));
            $manager->persist($user);
            array_push($usersArray, $user);

        }

        foreach ($categories as $category) {
            $manager->persist($category);
            array_push($categoriesArray, $category);
        }


        foreach ($blogPosts as $blogPost) {
            for ($i = 0; $i < rand(1, count($categoriesArray)); $i++) {
                $blogPost->addPostCategory($categoriesArray[array_rand($categoriesArray)]);
            }
            $manager->persist($blogPost);
            array_push($blogPostsArray, $blogPost);

        }
        foreach ($comments as $comment) {
            $comment->setUserId($usersArray[array_rand($usersArray)]);
            $comment->setPostId($blogPosts[array_rand($blogPosts)]);
            $manager->persist($comment);
        }


        $manager->flush();
    }
}
