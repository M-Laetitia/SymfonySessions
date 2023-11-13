<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Student;
use App\Form\ProgrammeType;
use App\Entity\ModuleFormation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sexe' , TextType::class)
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('firstName' , TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('lastName' , TextType::class)
            ->add('email' , EmailType::class)
            ->add('phoneNumber' , TextType::class)
            ->add('city' , TextType::class)
            ->add('Validate', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
