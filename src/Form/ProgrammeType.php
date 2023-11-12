<?php

namespace App\Form;

use App\Entity\Programme;
use App\Entity\ModuleFormation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('duration', IntegerType::class, [
            'constraints' => [
                new GreaterThan([
                    'value' => 0,
                    'message' => 'The duration must be a number greater than zero.'
                ]),
            ],
            'attr' => ['min' => 0],
        ])


        ->add('moduleFormation', EntityType::class, [
            'class' => ModuleFormation::class,
            'choice_label' => 'name', 
            'placeholder' => 'Choose a module', // Optional, adds an empty option at the beginning
        ])


        ->add('Validate', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
