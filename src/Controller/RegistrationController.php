<?php

namespace App\Controller;

use App\Entity\UserForm;
use App\Entity\Users;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegistrationController extends AbstractController
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = new Users();

        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();

            $user->setPassword($this->passwordHasher->hashPassword($user, $userData->getPassword()));
            $user->setRoles(["ROLE_USER"]);
            $user->setAvatar("UserAvatar.png");

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('login');
        }

        $response = new Response(null, $form->isSubmitted() ? 422 : 200);

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ], $response);
    }
}
