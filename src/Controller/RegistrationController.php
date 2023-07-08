<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use App\Entity\RegistrationForm;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager): Response
    {

        if(isset($_POST['email'])) {
            echo $_POST['email'];
        }

        return $this->render('registration/index.html.twig', [

        ]);
    }

    public function new(Request $request): Response
    {
        // creates a task object and initializes some data for this example
        $registrationForm = new RegistrationForm();
//        $task->setTask('Write a blog post');
//        $task->setDueDate(new \DateTimeImmutable('tomorrow'));

        $form=$this->createForm(RegistrationFormType::class, $registrationForm);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $userData = $form->getData();
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('homePage');
        }

        return $this->render('registration/index.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
}
