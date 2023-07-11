<?php

namespace App\DataFixtures;

use App\Entity\BlogPosts;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $loremIpsum="Tortor eu quisque eget proin eu lorem quis phasellus aliquam fusce et amet adipiscing purus rutrum commodo diam magna felis tincidunt phasellus adipiscing magna molestie.
Vivamus sem proin eu et leo interdum eu scelerisque interdum tristique amet enim metus vivamus vivamus dolor gravida maximus molestie phasellus amet et ipsum ut.";

        for($i=0;$i<5;$i++){
            $blogPost=new BlogPosts();
            $blogPost->setTitle("Title #".$i);
            $blogPost->setBody($loremIpsum);
            $blogPost->setImageFilename("Zebra_64abf2eaac043.jpg");
            $blogPost->setNumOfLikes(0);
            $manager->persist($blogPost);

        }

        $manager->flush();
    }
}
