<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Task;
use App\Repository\EmployeeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label'=>'Task Title',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'name',
                'query_builder' => function (EmployeeRepository $er) use ($options) {
                    return $er->serchTeamMember($options['departmentId']);
                },
                'label' => 'Assign To Team Member',
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description',TextType::class,[
                'label'=>'Description',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('priority',ChoiceType::class,[
                'label'=>'Priority',
                'choices' => [
                    'High' => 'High',
                    'Medium' => 'Medium',
                    'Low' => 'Low',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('status',ChoiceType::class,[
                'label'=>'Status',
                'choices' => [
                    'Pending' => 'Pending',
                    'In Progress' => 'In Progress',
                    'Completed' => 'Completed',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('deadline',DateType::class,[
                'label'=>'Deadline',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'departmentId' => null,
        ]);
    }
}
