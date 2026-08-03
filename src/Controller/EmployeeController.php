<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Salary;
use App\Entity\User;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/employee')]
final class EmployeeController extends AbstractController
{
    #[IsGranted('ROLE_HR')]
    #[Route(name: 'app_employee_index', methods: ['GET'])]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        return $this->render('employee/index.html.twig', [
            'employees' => $employeeRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_HR')]
    #[Route('/new', name: 'app_employee_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. User Account Creation
            $email = $form->get('email')->getData();
            $plainPassword = $form->get('plainPassword')->getData();

            if (!$email || !$plainPassword) {
                $this->addFlash('danger', 'Account Email and Password are required to create an employee profile.');
                return $this->render('employee/new.html.twig', [
                    'employee' => $employee,
                    'form' => $form,
                ]);
            }

            $user = new User();
            $user->setEmail($email);
            $user->setRoles(['ROLE_EMPLOYEE']);
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile|null $profilePicFile */
            $profilePicFile = $form->get('profilePic')->getData();
            if ($profilePicFile) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/profile_pics';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $newFilename = uniqid('profile_', true) . '.' . $profilePicFile->guessExtension();
                $profilePicFile->move($uploadDir, $newFilename);
                $user->setProfilePic($newFilename);
            }

            $entityManager->persist($user);
            $employee->setUser($user);

            // 2. Persist Employee Details
            $entityManager->persist($employee);

            // 3. Initial Salary Setup
            $salarySetup = $form->get('setInitialSalary')->getData();
            if ($salarySetup === 'yes') {
                $basicSalary = $form->get('basicSalary')->getData();
                if ($basicSalary) {
                    $salary = new Salary();
                    $salary->setEmployee($employee);
                    $salary->setMonth(date('F'));
                    $salary->setYear(date('Y'));
                    $salary->setBasicSalary((string)$basicSalary);
                    $salary->setBonus('0');
                    $salary->setDeduction('0');
                    $salary->setNetSalary((string)$basicSalary);
                    $salary->setPaymentStatus('Pending');
                    $entityManager->persist($salary);
                }
            }

            $entityManager->flush();
            $this->addFlash('success', 'Employee profile, User account & Salary setup created successfully!');

            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Please fill in all required employee onboarding fields correctly.');
        }

        return $this->render('employee/new.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        /** @var User|null $currentUser */
        $currentUser = $this->getUser();

        // HR & Admin can view any employee. Regular employees can only view their own profile.
        if (!$this->isGranted('ROLE_HR') && $employee->getUser() !== $currentUser) {
            $this->addFlash('danger', 'You can only view your own employee profile.');
            return $this->redirectToRoute('app_employee_deshboard');
        }

        return $this->render('employee/show.html.twig', [
            'employee' => $employee,
        ]);
    }

    #[IsGranted('ROLE_HR')]
    #[Route('/{id}/edit', name: 'app_employee_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Employee $employee, EntityManagerInterface $entityManager, EmployeeRepository $employeeRepository): Response
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Employee details updated successfully!');

            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Please fix errors in the employee form.');
        }

        return $this->render('employee/edit.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_HR')]
    #[Route('/{id}', name: 'app_employee_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Employee $employee, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$employee->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($employee);
            $entityManager->flush();
            $this->addFlash('warning', 'Employee record deleted.');
        }

        return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
    }
}
