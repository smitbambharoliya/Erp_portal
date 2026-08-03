<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Department;
use App\Entity\Employee;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // --- SECTION 1: USER ACCOUNT ---
            ->add('email', EmailType::class, [
                'mapped' => false,
                'label' => 'Account Email',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter an account email.']),
                    new Email(['message' => 'Please enter a valid email address.']),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => 'employee@company.com'],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Account Password',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter an account password.']),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => 'Set password'],
            ])
            ->add('profilePic', FileType::class, [
                'mapped' => false,
                'label' => 'Profile Picture',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG, PNG, WEBP, GIF)',
                    ])
                ],
                'attr' => ['class' => 'form-control'],
            ])

            // --- SECTION 2: PERSONAL & WORK DETAILS ---
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone Number',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Date of Birth',
                'widget' => 'single_text',
                'input' => 'string',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Gender',
                'attr' => ['class' => 'form-control'],
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
                'attr' => ['class' => 'form-control'],
            ])
            ->add('designation', TextType::class, [
                'label' => 'Designation',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Employee Status',
                'attr' => ['class' => 'form-control'],
                'choices' => [
                    'Active' => 'Active',
                    'Inactive' => 'Inactive',
                ],
            ])
            ->add('department', EntityType::class, [
                'class'        => Department::class,
                'choice_label' => 'name',
                'attr'         => ['class' => 'form-control'],
                'placeholder'  => 'Select Department',
                'required'     => true,
            ])

            // --- SECTION 3: INITIAL SALARY SETUP ---
            ->add('setInitialSalary', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Setup Initial Salary?',
                'choices' => [
                    'Yes, Setup Salary Now' => 'yes',
                    'No, Setup Later' => 'no',
                ],
                'data' => 'yes',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('basicSalary', TextType::class, [
                'mapped' => false,
                'label' => 'Basic Salary (₹)',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'e.g. 35000'],
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
