<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\Regex;

class EditUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isPasswordValid = [
            new Length(['min' => 10]),
            new Regex(['pattern' => '/\d+/', 'message' => 'Your password must have at least one digit']),
            new Regex(['pattern' => '/[A-Z]+/', 'message' => 'Your password must have at least one capital letter'])
        ];


        $builder
            ->add('username', TextType::class, ['required' => false, 'mapped' => false, 'label_format' => '%name%'])
            ->add('email', EmailType::class, ['required' => false, 'mapped' => false, 'label_format' => '%name%'])
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Old password',
                'constraints' => $isPasswordValid,
                'required' => false,
                'mapped' => false,
                'label_format' => '%name%'
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'New password',
                'constraints' => $isPasswordValid,
                'required' => false,
                'mapped' => false,
                'label_format' => '%name%'
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'required' => false,
                'mapped' => false,
                'data_class' => null,
                'label_format' => '%name%',
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/x-png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image document',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Edit', 'label_format' => '%name%']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
