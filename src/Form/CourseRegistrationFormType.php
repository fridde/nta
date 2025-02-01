<?php

namespace App\Form;

use App\Entity\CourseRegistration;
use App\Entity\Topic;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('User', EntityType::class, [
                'label' => 'Pedagog',
                'class' => User::class,
                'choices' => $options['users'],
                'choice_label' => 'FullName',
                'placeholder' => '---Välj lärare---',
                'attr' => ['data-action' => 'courseregistrationform#updateTopicsAvailable'],
            ])
            ->add('Topic', EntityType::class, [
                'label' => 'Tema',
                'class' => Topic::class,
                'choice_label' => 'Name',
                'placeholder' => '---Välj tema---',
                'attr' => ['data-courseregistrationform-target' => 'topicSelector'],
            ])
        ->add('Registrera', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CourseRegistration::class,
            'users' => [],
        ]);

        $resolver
            ->setAllowedTypes('users', 'array');
    }

}