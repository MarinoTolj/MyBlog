<?php

namespace App\Form;


use App\Entity\PostCategories;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;


class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var EntityManager $entityManager */
        $entityManager = $options['entity_manager'];
        $categories = $entityManager->getRepository(PostCategories::class)->findAll();
        $choices = [];

        foreach ($categories as $key => $value) {
            $choices[$value->getName()] = $value->getName();
        }

        $builder
            ->add('title', TextType::class, ['label' => 'Title', 'label_format' => '%name%', 'constraints' => [new Length(['min' => 5, 'max' => 40])]])
            ->add('body', TextareaType::class, ['label' => 'Body', 'label_format' => '%name%', 'constraints' => [new Length(['min' => 5, 'max' => 500])]])
            ->add('categories', ChoiceType::class, [
                'choices' => $choices,
                'expanded' => true,
                'multiple' => true,
                'mapped' => false,

            ])
            ->add('imageFilename', FileType::class, [
                'label' => 'Image',
                'data_class' => null,
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
            ->add('save', SubmitType::class, ['label' => 'Submit']);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
        $resolver->setRequired('entity_manager');
    }
}
