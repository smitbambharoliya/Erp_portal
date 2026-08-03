<?php

namespace App\Controller;

use App\Entity\Salary;
use App\Entity\User;
use App\Form\SalaryType;
use App\Repository\SalaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/salary')]
final class SalaryController extends AbstractController
{
    #[Route(name: 'app_salary_index', methods: ['GET'])]
    public function index(SalaryRepository $salaryRepository): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if ($this->isGranted('ROLE_HR') || $this->isGranted('ROLE_ADMIN')) {
            $salaries = $salaryRepository->findAll();
        } else {
            $employee = $user?->getEmployee();
            $salaries = $salaryRepository->findBy(['employee' => $employee]);
        }

        return $this->render('salary/index.html.twig', [
            'salaries' => $salaries,
        ]);
    }

    #[IsGranted('ROLE_HR')]
    #[Route('/new', name: 'app_salary_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $salary = new Salary();
        $form = $this->createForm(SalaryType::class, $salary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($salary);
            $entityManager->flush();
            $this->addFlash('success', 'Salary record created successfully!');

            return $this->redirectToRoute('app_salary_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Please fill in all required salary details correctly.');
        }

        return $this->render('salary/new.html.twig', [
            'salary' => $salary,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salary_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Salary $salary): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        // HR & Admin can view any salary slip. Regular employees can only view their own salary slip.
        if (!$this->isGranted('ROLE_HR') && $salary->getEmployee() !== $user?->getEmployee()) {
            $this->addFlash('danger', 'You can only view your own salary slips.');
            return $this->redirectToRoute('app_employee_deshboard');
        }

        return $this->render('salary/show.html.twig', [
            'salary' => $salary,
        ]);
    }

    #[IsGranted('ROLE_HR')]
    #[Route('/{id}/edit', name: 'app_salary_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Salary $salary, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SalaryType::class, $salary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Salary record updated successfully!');

            return $this->redirectToRoute('app_salary_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Please fix errors in the salary form.');
        }

        return $this->render('salary/edit.html.twig', [
            'salary' => $salary,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_HR')]
    #[Route('/{id}', name: 'app_salary_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Salary $salary, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $salary->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($salary);
            $entityManager->flush();
            $this->addFlash('warning', 'Salary record deleted.');
        }

        return $this->redirectToRoute('app_salary_index', [], Response::HTTP_SEE_OTHER);
    }
}
