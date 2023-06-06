<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '10'
                ],
                'label' => 'Nom : ',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('Brand', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '10'
                ],
                'label' => 'Marque : ',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('Price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '10',
                ],
                'label' => 'Prix : ',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('img', FileType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '10',
                    'accept' => 'image/*'
                ],
                'label' => 'Image : ',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '10'
                ],
                'label' => 'Description : ',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('Type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'nametype',
                'choice_value' => 'id',
                'placeholder' => 'Choisis un type',
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '10'
                ],
                'label' => 'Type : ',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
