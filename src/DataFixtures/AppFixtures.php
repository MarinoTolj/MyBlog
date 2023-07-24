<?php

namespace App\DataFixtures;

use App\DataFixtures\ORM\AppNativeLoader;
use App\Entity\BlogPosts;
use App\Entity\PostTranslations;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Throwable\LoadingThrowable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Generator;


class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager, Generator $generator)
    {
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
        $this->generator = $generator;
    }

    /**
     * @throws LoadingThrowable
     */
    public function load(ObjectManager $manager)
    {
        $loader = new AppNativeLoader($this->hasher, $this->generator);
        $databaseData = $loader->loadFile(__DIR__ . '/fixtures/dataFixtures.yaml')->getObjects();
        $admin = new Users();
        $admin->setUsername("Admin");
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setEmail("admin@admin");
        $password = $this->hasher->hashPassword($admin, "Adminadmin1");
        $admin->setPassword($password);
        $admin->setAvatar("man_64b3e66cc7a49.png");
        $manager->persist($admin);
        $blogPosts = [];

        foreach ($databaseData as $data) {
            if ($data instanceof BlogPosts) {
                array_push($blogPosts, $data);
            }

            $manager->persist($data);
        }

        for ($i = 0; $i < count($blogPosts); $i++) {

            for ($j = 0; $j < 2; $j++) {
                $postTranslations = new PostTranslations();
                $postTranslations->setTitle($loader->getFakerGenerator()->text(20));
                $postTranslations->setBody($loader->getFakerGenerator()->text());
                $postTranslations->setLocale($j % 2 === 0 ? "es" : "hr");
                $postTranslations->setPostId($blogPosts[$i]);
                $manager->persist($postTranslations);
            }
        }

        $manager->flush();
    }
}
