<?php


namespace App\Tests\Controller;

use App\Form\UserFormType;
use App\Repository\UsersRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\Form\Test\TypeTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testSuccessfulLogin()
    {

        $client = static::createClient();
        $crawler = $client->request('GET', '/en/login');
        $this->assertStatusCode(200, $client);

        $formTest = $crawler->selectButton('Sign in')->form();

        $formTest->setValues(['email' => 'admin@admin', 'password' => 'Adminadmin1']);

        $client->submit($formTest);
        $this->assertStatusCode(302, $client);
    }

}