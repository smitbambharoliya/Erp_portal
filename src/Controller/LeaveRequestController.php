<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\LeaveRequest;
use App\Entity\User;
use App\Form\LeaveRequestType;
use App\Repository\LeaveRequestRepository;
use App\Service\LeaveRequestService;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/leave/request')]
final class LeaveRequestController extends AbstractController
{
    #[Route(name: 'app_leave_request_index', methods: ['GET'])]
    public function index(LeaveRequestRepository $leaveRequestRepository): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if ($this->isGranted('ROLE_HR')) {
            $leaves = $leaveRequestRepository->findAll();
        } else {
            $employee = $user?->getEmployee();
            $leaves = $leaveRequestRepository->findBy(['employee' => $employee]);
        }
        return $this->render('leave_request/index.html.twig', [
            'leave_requests' => $leaves,
        ]);
    }

    #[Route('/new', name: 'app_leave_request_new', methods: ['GET', 'POST'])]
    public function new(Request $request,
     EntityManagerInterface $entityManager
     ): Response
    {
        $leaveRequest = new LeaveRequest();

        $form = $this->createForm(LeaveRequestType::class, $leaveRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User|null $user */
            $user = $this->getUser();
            $leaveRequest->setEmployee($user->getEmployee());
            $leaveRequest->setStatus("Pending");
            $leaveRequest->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($leaveRequest);
            $entityManager->flush();
            $this->addFlash('success', 'Leave request created successfully!');

            return $this->redirectToRoute('app_employee_deshboard', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Please fill in all required leave application details correctly.');
        }

        return $this->render('leave_request/new.html.twig', [
            'leave_request' => $leaveRequest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_leave_request_show', methods: ['GET'])]
    public function show(LeaveRequest $leaveRequest): Response
    {
        return $this->render('leave_request/show.html.twig', [
            'leave_request' => $leaveRequest,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_leave_request_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LeaveRequest $leaveRequest, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LeaveRequestType::class, $leaveRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_leave_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('leave_request/edit.html.twig', [
            'leave_request' => $leaveRequest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_leave_request_delete', methods: ['POST'])]
    public function delete(Request $request, LeaveRequest $leaveRequest, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$leaveRequest->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($leaveRequest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_leave_request_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/approve',name:'app_leave_request_approve')]
    public function approve(
        LeaveRequestService $leaveRequestService,
        LeaveRequest $leaveRequest,
        NotificationService $notificationService
    ): Response
    {
        $leaveRequestService->approve($leaveRequest);

        $user = $leaveRequest->getEmployee()?->getUser();
        if ($user) {
            $notificationService->notify($user, 'Leave Request Approved', 'Your leave request has been approved by HR.');
        }

        $this->addFlash('success', 'Leave request approved successfully!');
        
        return $this->redirectToRoute('app_hr_dashboard');
    }

    #[Route('/{id}/reject',name:'app_leave_request_reject')]
    public function reject(
        LeaveRequestService $leaveRequestService,
        LeaveRequest $leaveRequest,
        NotificationService $notificationService
    ): Response
    {
        $leaveRequestService->reject($leaveRequest);

        $user = $leaveRequest->getEmployee()?->getUser();
        if ($user) {
            $notificationService->notify($user, 'Leave Request Rejected', 'Your leave request has been rejected by HR.');
        }

        $this->addFlash('success', 'Leave request rejected successfully!');
        
        return $this->redirectToRoute('app_hr_dashboard');
    }
}
