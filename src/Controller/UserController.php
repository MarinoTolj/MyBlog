<?php

namespace App\Controller;

use App\Entity\PostCategories;
use App\Entity\Users;
use App\Form\CategoryType;
use App\Form\EditUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

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
            return $this->redirectToRoute('user_profile', ['userId' => $this->getUser()->getId()]);
        }

        return $this->render('user/index.html.twig', ['categories' => $categories, 'form' => $addCategoryForm->createView()]);
    }

    public function editCategory(EntityManagerInterface $entityManager, $id, Request $request): Response
    {
        $category = $entityManager->getRepository(PostCategories::class)->findBy(['id' => $id])[0];

        $editCategoryForm = $this->createForm(CategoryType::class, $category);

        $editCategoryForm->handleRequest($request);

        if ($editCategoryForm->isSubmitted() && $editCategoryForm->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('user_profile', ['userId' => $this->getUser()->getId()]);
        }

        return $this->render('category/editCategory.html.twig', ['form' => $editCategoryForm->createView(), 'categoryId' => $category->getId()]);
    }

    public function deleteCategory(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger, int $id): Response
    {
        $currentCategory = $entityManager->getRepository(PostCategories::class)->findBy(['id' => $id]);

        if (!$currentCategory) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        $currentCategory = $currentCategory[0];

        $entityManager->remove($currentCategory);
        $entityManager->flush();

        return new Response("All Good");
    }


    public function editProfile(EntityManagerInterface $entityManager, $userId, Request $request, SluggerInterface $slugger): Response
    {
        $currentUser = $entityManager->getRepository(Users::class)->findBy(['id' => $userId]);
        $editedUser = new EditUserFormType();

        if (!$currentUser) {
            throw $this->createNotFoundException(
                'No user of id: ' . $userId
            );
        }
        $currentUser = $currentUser[0];
        $newFilename = $currentUser->getAvatar();

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
                $imageFile = $form->get("avatar")->getData();

                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '_' . uniqid() . '.' . $imageFile->guessExtension();

                    try {
                        $imageFile->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        var_dump($e);
                    }
                }
                $currentUser->setAvatar($newFilename);
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
