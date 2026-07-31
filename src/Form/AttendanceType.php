<?php

namespace App\Form;

use App\Entity\Attendance;
use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttendanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('date', DateType::class, 
            [
                'widget' => 'single_text'])
            ->add('checkIn', TimeType::class, [
                'widget' => 'single_text'
            ])
            ->add('checkOut', TimeType::class, [
                'widget' => 'single_text'
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Present' => 'Present',
                    'Absent' => 'Absent',
                    'Late' => 'Late',
                    'Holiday' => 'Holiday',
                    'Half Day' => 'Half Day',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('workingHours',TimeType::class, [
                'widget' => 'single_text'

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Attendance::class,
        ]);
    }
}
