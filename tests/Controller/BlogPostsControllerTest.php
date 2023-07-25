<?php

namespace App\Tests\Controller;

use App\Entity\BlogPosts;
use App\Form\UserFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\Form\Test\TypeTestCase;


class BlogPostsControllerTest extends WebTestCase
{

    public function testNewPost()
    {
        $formData = [
            'title' => 'TestTitle',
            'body' => 'Test Body',
            'locale' => 'en',
            'imageFilename' => 'path\to\imageFile.png'
        ];

        $client = static::createClient();
        $crawler = $client->request('GET', '/en/posts/new');
        $this->assertStatusCode(302, $client);


        $userRepository = static::getContainer()->get(UsersRepository::class);
        $admin = $userRepository->findOneBy(['email' => 'admin@admin']);

        $client->loginUser($admin);
        $crawler = $client->request('GET', '/en/posts/new');
        $this->assertStatusCode(200, $client);

        $formTest = $crawler->selectButton('Submit')->form();
        foreach ($formData as $key => $value) {
            $formTest->setValues(['blog_post[' . $key . ']' => $value]);
        }

        $client->submit($formTest);

        $this->assertStatusCode(302, $client);

        $client->followRedirect();
        $this->assertStatusCode(200, $client);

        $client->request('GET', "/en/posts/9999");
        $this->assertStatusCode(404, $client);

    }


}