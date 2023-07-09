<?php

namespace App\Controller;

use App\Entity\UserForm;
use App\Entity\Users;
use App\Form\UserFormType;
use App\Form\UserLoginFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $userForm = new UserForm();
        $user=new Users();

        $form=$this->createForm(UserLoginFormType::class, $userForm);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();

            var_dump($userData);

//            $entityManager->persist($user);
//
//            $entityManager->flush();
            //return $this->redirectToRoute('homePage');
        }

        return $this->render('registration/index.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
}
