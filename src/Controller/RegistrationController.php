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
        $user=new Users();

        $form=$this->createForm(UserFormType::class, $userForm);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();

            if($this->isPasswordValid($userData, $form)){

                $user->setPassword($this->passwordHasher->hashPassword($user, $userData->getPassword()));
                $user->setUsername($userData->getUsername());
                $user->setEmail($userData->getEmail());
                $user->setUserRoles("USER");

                $entityManager->persist($user);

                $entityManager->flush();
                return $this->redirectToRoute('login');
            }
        }

        return $this->render('registration/index.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    public function isPasswordValid(mixed $userData, FormInterface $form):FormError | bool{
        $userPassword=$userData->getPassword();
        if($userPassword!==$userData->getPasswordConfirm()){
            $form->addError(new FormError("Password mismatch"));
            return false;
        }
        else if(strlen($userPassword)<10){
            $form->addError(new FormError("Password length must be larger than 10 characters"));
            return false;
        }else if(!preg_match("/\d+/",$userPassword)){
            $form->addError(new FormError("Password must have at least one number, one letter and one capital letter"));
            return false;
        }
        else if(!preg_match("/[A-Z]+/",$userPassword)){
            $form->addError(new FormError("Password must have at least one capital letter"));
            return false;
        }

        return true;
    }
}
