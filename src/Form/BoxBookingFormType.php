<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Period;
use App\Entity\Topic;
use App\Entity\User;
use App\Utils\Coll;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoxBookingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $periods = Coll::create($options['periods']);
        /** @var Period $closestPeriod */
        $closestPeriod = $periods->first();
        $boxesLeft = $options['boxes_left'];
        
        $builder
            ->add('Period', EntityType::class, [
                'label' => 'Termin',
                'class' => Period::class,
                'choices' => $options['periods'],
                'attr' => ['data-action' => 'bookingform#updateTopicsAvailable']
            ])
            ->add('Topic', EntityType::class, [
                'label' => 'Tema',
                'class' => Topic::class,
                'choice_label' => 'Name',
                'placeholder' => '---Välj tema---',
                'choice_attr' => fn(Topic $t) => $boxesLeft[$closestPeriod->id][$t->id] === 0 ? ['disabled' => 'disabled'] : [],
                'attr' => ['data-bookingform-target' => 'topicSelector' ],
                'help' => 'Om ett tema inte går att välja beror det på att det inte finns tillräckligt med lådor kvar.'
            ])
            ->add('BoxOwner', EntityType::class, [
                'label' => 'Pedagog som ska ha lådan',
                'placeholder' => '---Välj lärare---',
                'class' => User::class,
                'choices' => $options['users'],
            ])
            ->add('NrStudents', IntegerType::class, [
                'label' => 'Antal elever som ska nyttja lådan/lådorna',
                'help' => 'För hur många elever ska materialet i dessa lådor (eller denna låda) räcka?',
                'data' => 30,
            ])
            ->add('NrBoxes', null, ['label' => 'Antal lådor'])
            ->add('Send', SubmitType::class, ['label' => 'Boka']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'users' => [],
            'periods' => [],
            'boxes_left' => []
        ]);

        $resolver
            ->setAllowedTypes('users', 'array')
            ->setAllowedTypes('periods', 'array')
            ->setAllowedTypes('boxes_left', 'array');
    }
}
