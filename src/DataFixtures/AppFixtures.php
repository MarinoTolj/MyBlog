<?php

namespace App\DataFixtures;

use App\Entity\BlogPosts;
use App\Entity\Comments;
use App\Entity\PostCategories;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager)
    {
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
    }


    public function load(ObjectManager $manager): void
    {

        $blogPosts = [];
        $loremIpsum = "Tortor eu quisque eget proin eu lorem quis phasellus aliquam fusce et amet adipiscing purus rutrum commodo diam magna felis tincidunt phasellus adipiscing magna molestie.
Vivamus sem proin eu et leo interdum eu scelerisque interdum tristique amet enim metus vivamus vivamus dolor gravida maximus molestie phasellus amet et ipsum ut.";
//        $conn = $this->entityManager->getConnection();
//
//
//        $sql = '
//            ALTER TABLE blog_posts AUTO_INCREMENT = 1
//            ';
//
//        $conn->executeQuery($sql);

        for ($i = 0; $i < 50; $i++) {
            $blogPost = new BlogPosts();
            $blogPost->setTitle("Title #" . $i);
            $blogPost->setBody($loremIpsum);
            $blogPost->setImageFilename("Zebra1_64ae96b5cc929.jpg");
            $blogPost->setNumOfLikes(0);
            array_push($blogPosts, $blogPost);
            $manager->persist($blogPost);
        }

        $admin = new Users();
        $admin->setUsername("Admin");
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setEmail("admin@admin");
        $password = $this->hasher->hashPassword($admin, "Adminadmin1");
        $admin->setPassword($password);
        $admin->setAvatar("man_64ae9a2fe3109.png");

        $manager->persist($admin);

        for ($i = 0; $i < 5; $i++) {
            $user = new Users();
            $comment = new Comments();
            $user->setUsername("User#" . $i);
            $user->setRoles(["ROLE_USER"]);
            $user->setEmail("user" . $i . "@user");
            $password = $this->hasher->hashPassword($user, "Userpassword" . $i);
            $user->setPassword($password);
            $user->setAvatar("user_64ae975453bf9.png");
            $manager->persist($user);


            $comment->setBody($loremIpsum);
            $comment->setPostId($blogPosts[array_rand($blogPosts)]);
            $comment->setUserId($user);
            $manager->persist($comment);
        }

        for ($i = 0; $i < 5; $i++) {
            $category = new PostCategories();
            $category->setName("Category#" . $i);
            $manager->persist($category);
        }

        $manager->flush();

    }
}
