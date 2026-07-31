<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Task;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\NumberType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextareaType::class,[
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('startDate', DateType::class,[
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('endDate', DateType::class,[
                'label'=>'select end date or Expacted end date', 
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('status', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('budget', NumberType::class,[
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        
            ->add('task', ChoiceType::class, [
                'label'=>'task',
                'choices' => [
                    'Frontend Developer' => 'Frontend Developer',
                    'Backend Developer' => 'Backend Developer',
                    'Full Stack Developer' => 'Full Stack Developer',
                    'React Developer' => 'React Developer',
                    'Angular Developer' => 'Angular Developer',
                    'Vue Developer' => 'Vue Developer',
                    'UI/UX Designer' => 'UI/UX Designer',
                    'Project Manager' => 'Project Manager',
                    'QA Engineer' => 'QA Engineer',
                    'DevOps Engineer' => 'DevOps Engineer',
                    'Data Scientist' => 'Data Scientist',
                    'Machine Learning Engineer' => 'Machine Learning Engineer',
                    'Data Analyst' => 'Data Analyst',
                    'Business Analyst' => 'Business Analyst',
                    'Product Manager' => 'Product Manager',
                    'Scrum Master' => 'Scrum Master',
                    'Agile Coach' => 'Agile Coach',
                    'Project Coordinator' => 'Project Coordinator',
                    'Team Lead' => 'Team Lead',
                    'Senior Developer' => 'Senior Developer',
                    'Junior Developer' => 'Junior Developer',
                    'Intern' => 'Intern',
                    'Consultant' => 'Consultant',
                    'Freelancer' => 'Freelancer',
                    'Contractor' => 'Contractor',
                    'Part-time' => 'Part-time',
                    'Full-time' => 'Full-time',
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
            'data_class' => Project::class,
        ]);
    }
}
