<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Session;
use App\Entity\Formation;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('formation' , TextType::class)

            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                // 'choice_label' => 'name',
            ])

            ->add('name', TextType::class)
            ->add('startDate' , DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('endDate' , DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('nbPlaceTotal' , IntegerType::class)


         
            ->add('user', EntityType::class, [
                'class' => User::class,
                // 'choice_label' => 'title',
            ])

            

            ->add('Validate', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
