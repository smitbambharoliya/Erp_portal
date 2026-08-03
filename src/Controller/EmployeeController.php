<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\User;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employee);
            $entityManager->flush();
            $this->addFlash('success', 'Employee profile created successfully!');

            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Please fill in all required employee fields correctly.');
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
