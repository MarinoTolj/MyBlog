<?php

namespace App\DataFixtures\ORM;

use Faker\Provider\Base as BaseProvider;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppProvider extends BaseProvider
{

    public function __construct(UserPasswordHasherInterface $hasher, Generator $generator)
    {
        $this->hasher = $hasher;
        parent::__construct($generator);

    }

    public function getRandomImage()
    {
        $images = ["BlogPostImage1.jpg", "BlogPostImage2.jpg"];
        return $images[array_rand($images)];
    }

    public function getPasswordHash($user, string $password): string
    {
        return $this->hasher->hashPassword($user, $password);
    }

}