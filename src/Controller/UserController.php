<?php

namespace App\Controller;

use App\Entity\PostCategories;
use App\Entity\Users;
use App\Form\CategoryType;
use App\Form\EditUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function Symfony\Component\String\b;

class UserController extends AbstractController
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $categories = $entityManager->getRepository(PostCategories::class)->findAll();
        $newCategory = new PostCategories();
        $addCategoryForm = $this->createForm(CategoryType::class, $newCategory);

        $addCategoryForm->handleRequest($request);

        if ($addCategoryForm->isSubmitted() && $addCategoryForm->isValid()) {


            $entityManager->persist($newCategory);
            $entityManager->flush();
        }

        return $this->render('user/index.html.twig', ['categories' => $categories, 'form' => $addCategoryForm->createView()]);
    }

//    public function editCategory(EntityManagerInterface $entityManager, int $id): Response
//    {
//        $category=$entityManager->getRepository(PostCategories::class)->findBy(['id'=>$id]);
//
//        retr
//
//    }

    public function editProfile(EntityManagerInterface $entityManager, $userId, Request $request): Response
    {
        $currentUser = $entityManager->getRepository(Users::class)->findBy(['id' => $userId]);
        $editedUser = new EditUserFormType();
        $newUser = new Users();


        if (!$currentUser) {
            throw $this->createNotFoundException(
                'No user of id: ' . $userId
            );
        }
        $currentUser = $currentUser[0];

        $form = $this->createForm(EditUserFormType::class, $editedUser);

        $form->get("username")->setData($currentUser->getUsername());
        $form->get("email")->setData($currentUser->getEmail());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $oldPassword = $form->get("oldPassword")->getData();
            $newPassword = $form->get("newPassword")->getData();
            $newUsername = $form->get("username")->getData();
            $newEmail = $form->get("email")->getData();

            if ($oldPassword !== null && !$this->passwordHasher->isPasswordValid($currentUser, $oldPassword)) {
                $form->addError(new FormError("Old password is not correct"));
            } else {

                if ($newPassword !== null) {
                    $currentUser->setPassword($this->passwordHasher->hashPassword($currentUser, $newPassword));
                }
                $currentUser->setUsername($newUsername);
                $currentUser->setEmail($newEmail);
                $entityManager->persist($currentUser);
                $entityManager->flush();
                return $this->redirectToRoute("user_profile", ['userId' => $userId]);
            }
        }

        return $this->render('user/editProfile.html.twig', ['form' => $form->createView()]);
    }
}
