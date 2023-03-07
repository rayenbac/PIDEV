<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'donnez votre email'
                ],
            ])
            
            ->add('FirstName', TextType::class, [
                'attr' => [
                    'placeholder' => 'donnez votre prénom'
                ],
            ])
            ->add('LastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'donnez votre nom'
                ],
            ])
            ->add('Adresse', TextType::class, [
                'attr' => [
                    'placeholder' => 'donnez votre adresse'
                ],
            ])
            ->add('BirthDate', DateType::class, [
                'widget' => 'choice',
                'input'  => 'datetime_immutable',
                'years' => range(date('Y'), 1800)
            ])
            ->add('PhoneNumber', TextType::class, [
                'attr' => [
                    'class' => 'input-group',
                ],
            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique.',
                
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe.'
                    ]
                ],
                'second_options' => [
                   
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre mot de passe.'
                    ]
                ]
            ])
            ->add('Roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices'  => [
                  'Patient' => 'ROLE_PATIENT',
                  'Medecin' => 'ROLE_MEDECIN',
                ],
            ])
            ->add('Gender', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'choices'  => [
                  'Femme' => 'F',
                  'Homme' => 'H',
                ],
            ])

            

           

            ->add('file', FileType::class, [
                'required' => false,
                'constraints' => [
                    new File(
                        [

                            'mimeTypes' => [
                                'image/gif',
                                'image/jpg',
                                'image/jpeg',
                                'image/png',
                                'image/x-png'
                            ],
                            'mimeTypesMessage' => 'image invalide',
                        ]
                    )
                ],
            ])
            

            ->add('save', SubmitType::class, [
                'label' => 'Creer compte ',
                'attr' => ['class' => 'save'],
            ]);
        ;

        $builder->get('Roles')
        ->addModelTransformer(new CallbackTransformer(
            function ($rolesArray) {
                 // transform the array to a string
                 return count($rolesArray)? $rolesArray[0]: null;
            },
            function ($rolesString) {
                 // transform the string back to an array
                 return [$rolesString];
            }
    ));
    }

   

    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
