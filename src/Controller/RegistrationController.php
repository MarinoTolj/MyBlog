<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use App\Entity\UserForm;
use App\Entity\Users;
use App\Form\UserFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class RegistrationController extends AbstractController
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {

        $userForm = new UserForm();
        $user = new Users();

        $form = $this->createForm(UserFormType::class, $userForm);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();


            $user->setPassword($this->passwordHasher->hashPassword($user, $userData->getPassword()));
            $user->setUsername($userData->getUsername());
            $user->setEmail($userData->getEmail());
            $user->setRoles(["ROLE_USER"]);
            $user->setAvatar("user_64ae975453bf9.png");

            $entityManager->persist($user);

            $entityManager->flush();
            return $this->redirectToRoute('login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
