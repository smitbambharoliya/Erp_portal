<?php

namespace App\Form;

use App\Entity\LeaveRequest;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeaveRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('leaveType', TextType::class, [
                'label' => 'write the reason for the leave',
                'attr' => [ 
                    'class' => 'form-control',
                ],
                'invalid_message' => 'Please write the reason for the leave',
            ])
            ->add('startDate', DateType::class, [
                'label' => 'select start date',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'invalid_message' => 'Please select a valid start date',
                'attr' => [
                    'class' => 'js-datepicker',
                ],
            ])
            ->add('endDate', DateType::class, [
                'label' => 'select end date',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'invalid_message' => 'Please select a valid end date',
                'attr' => [
                    'class' => 'js-datepicker',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LeaveRequest::class,
        ]);
    }
}
