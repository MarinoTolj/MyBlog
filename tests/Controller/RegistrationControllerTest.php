<?php

namespace App\Tests\Controller;

use App\Form\UserFormType;
use App\Repository\UsersRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\Form\Test\TypeTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testSuccessfulRegistration()
    {
        $formData = [
            'username' => 'admin',
            'email' => 'admin@admin',
        ];

        $client = static::createClient();
        $crawler = $client->request('GET', '/en/registration');
        $this->assertStatusCode(200, $client);

        $formTest = $crawler->selectButton('Submit')->form();
        foreach ($formData as $key => $value) {
            $formTest->setValues(['user_form[' . $key . ']' => $value]);
        }
        $formTest->setValues(['user_form[password][first]' => 'Adminadmin1', 'user_form[password][second]' => 'Adminadmin1']);

        $client->submit($formTest);
        $this->assertStatusCode(302, $client);

    }

}