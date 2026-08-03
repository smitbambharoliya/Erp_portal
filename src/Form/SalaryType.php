<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Salary;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalaryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentYear = (int) date('Y');

        $builder
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'name',
                'label' => 'Select Employee',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('month', ChoiceType::class, [
                'label' => 'Month',
                'choices' => [
                    'January'   => 'January',
                    'February'  => 'February',
                    'March'     => 'March',
                    'April'     => 'April',
                    'May'       => 'May',
                    'June'      => 'June',
                    'July'      => 'July',
                    'August'    => 'August',
                    'September' => 'September',
                    'October'   => 'October',
                    'November'  => 'November',
                    'December'  => 'December',
                ],
                'data' => date('F'),
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('year', ChoiceType::class, [
                'label' => 'Year',
                'choices' => [
                    (string)$currentYear       => (string)$currentYear,
                    (string)($currentYear - 1) => (string)($currentYear - 1),
                    (string)($currentYear + 1) => (string)($currentYear + 1),
                ],
                'data' => (string)$currentYear,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('basicSalary', TextType::class, [
                'label' => 'Basic Salary (₹)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'e.g. 35000',
                ],
            ])
            ->add('bonus', TextType::class, [
                'label' => 'Bonus (₹)',
                'required' => false,
                'empty_data' => '0',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '0',
                ],
            ])
            ->add('deduction', TextType::class, [
                'label' => 'Deductions (₹)',
                'required' => false,
                'empty_data' => '0',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '0',
                ],
            ])
            ->add('netSalary', TextType::class, [
                'label' => 'Net Salary (₹)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Total In-Hand Salary',
                ],
            ])
            ->add('paymentStatus', ChoiceType::class, [
                'label' => 'Payment Status',
                'choices' => [
                    'Paid'    => 'Paid',
                    'Pending' => 'Pending',
                    'Unpaid'  => 'Unpaid',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salary::class,
        ]);
    }
}
