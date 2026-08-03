<?php

namespace App\Form;

use App\Entity\Attendance;
use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'label' => 'Select Employee',
                'placeholder' => '-- Select Employee --',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'input' => 'string',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('checkIn', TextType::class, [
                'label' => 'Check In Time (HH:MM:SS)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '09:00:00',
                ],
            ])
            ->add('checkOut', TextType::class, [
                'label' => 'Check Out Time (HH:MM:SS)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '17:30:00',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Attendance Status',
                'choices' => [
                    'Present'  => 'present',
                    'Absent'   => 'absent',
                    'Late'     => 'late',
                    'Half Day' => 'half_day',
                    'Holiday'  => 'holiday',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('workingHours', TextType::class, [
                'label' => 'Working Hours',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'e.g. 8 hrs 30 mins',
                ],
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
