<?php

namespace App\DataFixtures\ORM;

use Faker\Provider\Base as BaseProvider;
use Faker\Generator;

class AppProvider extends BaseProvider
{

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);

    }

    public function getRandomImage()
    {
        $images = ["BlogPostImage1.jpg", "BlogPostImage2.jpg"];
        return $images[array_rand($images)];
    }

}