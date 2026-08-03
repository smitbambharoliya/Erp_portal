<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'] ?? false;

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'user@example.com',
                ],
                'constraints' => [
                    new NotBlank(message: 'Please enter an email address'),
                ],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Account Role',
                'mapped' => false,
                'choices' => [
                    'Employee' => 'ROLE_EMPLOYEE',
                    'HR Manager' => 'ROLE_HR',
                    'Administrator' => 'ROLE_ADMIN',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'placeholder' => 'Select Role',
                'constraints' => [
                    new NotBlank(message: 'Please select a role'),
                ],
            ]);

        $passwordOptions = [
            'label' => 'Password',
            'mapped' => false,
            'required' => !$isEdit,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => $isEdit ? 'Leave blank to keep existing password' : 'Enter password',
                'autocomplete' => 'new-password',
            ],
            'constraints' => [],
        ];

        if (!$isEdit) {
            $passwordOptions['constraints'][] = new NotBlank(message: 'Please enter a password');
            $passwordOptions['constraints'][] = new Length(
                min: 6,
                minMessage: 'Your password should be at least {{ limit }} characters',
                max: 4096
            );
        } else {
            $passwordOptions['constraints'][] = new Length(
                min: 6,
                minMessage: 'Your password should be at least {{ limit }} characters',
                max: 4096
            );
        }

        $builder->add('plainPassword', PasswordType::class, $passwordOptions);

        $builder->add('profilePicFile', FileType::class, [
            'label' => 'Profile Picture (JPG, PNG, WebP)',
            'mapped' => false,
            'required' => false,
            'attr' => [
                'class' => 'form-control',
                'accept' => 'image/jpeg,image/png,image/webp',
            ],
            'constraints' => [
                new File(
                    maxSize: '5M',
                    mimeTypes: [
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                    ],
                    mimeTypesMessage: 'Please upload a valid image file (JPEG, PNG, WebP)'
                ),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
        ]);

        $resolver->setAllowedTypes('is_edit', 'bool');
    }
}
