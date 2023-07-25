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

        foreach ($databaseData as $data) {
            $manager->persist($data);
        }

        $manager->flush();
    }
}
