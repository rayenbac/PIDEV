<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\Symfony\Component\Form\FormView;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints\File;




class EvenementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomEvenement')
            ->add('LieuEvenement')
            ->add('DateEvenement' , DateType::class, [
                'widget' => 'choice'])
            ->add('DescriptionEvenement')
            ->add('save',SubmitType::class)
            ->add('NbrDePlaces')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'En ligne' => 'en ligne',
                    'Présentiel' => 'Présentiel',
                    
                ],
                
            ])
            ->add('Heure')
            ->add('Image', FileType::class, [
                

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ;

            
            

        
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
