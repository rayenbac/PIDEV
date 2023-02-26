<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        [

                            'mimeTypes' => [
                                'image/gif',
                                'image/jpg',
                                'image/jpeg',
                                'image/png',
                                'image/x-png',
                                'image/jfif',
                            ],
                            'mimeTypesMessage' => 'image invalide',
                        ]
                    )
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom du produit:',
            ])
            ->add('description', TextType::class, [
                'label' => 'description:'
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'categoryName',
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('price', NumberType::class, [
                'label' => 'prix:'
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'quantité:'
            ])

            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
