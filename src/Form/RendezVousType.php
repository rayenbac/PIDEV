<?php

namespace App\Form;

use App\Entity\RendezVous;
use App\Entity\Medecin;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;






class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'label'=>'Nom', 


                "attr"=>[
                    "class"=>   "form-control"
                ]
            ])
            
            ->add('prenom',TextType::class,[
                "attr"=>[
                    "class"=>   "form-control"
                ]
            ])
            ->add('cause',TextType::class,[
                "attr"=>[
                    "class"=>   "form-control"
                ]
            ])
            ->add('dateRV', DateType::class, [
                'label' => 'Date Rendez-vous',
                'widget' => 'single_text',
                'html5' => true,
                'data' => new \DateTimeImmutable('2000-01-01')])
                
            ->add('description',TextareaType::class,[
                "attr"=>[
                    "class"=>   "form-control",
                    'widget' => 'single_text'
                ]
            ])
            ->add('medecin', EntityType::class, [
                'label'=>'.', 

                'class' => Medecin::class,
                'choice_label' => 'nom',
                
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }
}
