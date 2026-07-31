<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Attendance;
use App\Entity\Department;
use App\Entity\Document;
use App\Entity\Employee;
use App\Entity\LeaveRequest;
use App\Entity\Salary;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('phone', NumberType::class, [
                'label' => 'Phone',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Date of Birth',
                'widget' => 'single_text',
                'input' => 'string',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Gender',
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => [
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Other' => 'Other',
                ],
            ])
            ->add('joiningDate', DateType::class, [
                'label' => 'Joining Date',
                'widget' => 'single_text',
                'input' => 'string',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('designation', TextType::class, [
                'label' => 'Designation',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => [
                    'Active' => 'Active',
                    'Inactive' => 'Inactive',
                ],
            ])

            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'User Account',
                'placeholder' => 'Select User Account',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('department', EntityType::class, [
                'class'        => Department::class,
                'choice_label' => 'name',
                'attr'         => ['class' => 'form-control'],
                'placeholder'  => 'Select Department',
                'required'     => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
