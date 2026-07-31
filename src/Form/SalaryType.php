<?php

namespace App\Form;

use App\Entity\Salary;
use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalaryType extends AbstractType
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
            ->add('month',DateType::class,[
                'widget' =>'choice',
                'months'=> range(1,12),
                'label'=>'Month',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('year',DateType::class,[
                'widget' => 'choice',
                'years'=> range(date('Y'), date('Y')+100),
                'label'=>'Year',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('basicSalary',NumberType::class,[
                'label'=>'Basic Salary',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('bonus',NumberType::class,[
                'label'=>'Bonus',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('deduction',NumberType::class,[
                'label'=>'Deduction',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('netSalary',NumberType::class,[
                'label'=>'Net Salary',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('paymentStatus',ChoiceType::class,[
                'label'=>'Payment Status',
                'choices' => [
                    'Paid' => 'Paid',
                    'Unpaid' => 'Unpaid',
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
