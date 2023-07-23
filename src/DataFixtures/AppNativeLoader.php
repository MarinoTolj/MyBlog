<?php

namespace App\DataFixtures\ORM;

use App\DataFixtures\Providers\HashPasswordProvider;
use Faker\Generator;
use Nelmio\Alice\Loader\NativeLoader;
use Faker\Generator as FakerGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppNativeLoader extends NativeLoader
{
    public function __construct(UserPasswordHasherInterface $hasher, Generator $generator)
    {
        $this->hasher = $hasher;
        parent::__construct($generator);
    }

    protected function createFakerGenerator(): FakerGenerator
    {
        $generator = parent::createFakerGenerator();
        $generator->addProvider(new AppProvider($this->hasher, $generator));
        return $generator;
    }
}