<?php

namespace App\Controller;

use App\Entity\Attendance;
use App\Entity\User;
use App\Event\AttendanceCheckedOutEvent;
use App\Form\AttendanceType;
use App\Repository\AttendanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('/attendance')]
final class AttendanceController extends AbstractController
{
    #[Route(name: 'app_attendance_index', methods: ['GET'])]
    public function index(AttendanceRepository $attendanceRepository): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if ($this->isGranted('ROLE_HR') || $this->isGranted('ROLE_ADMIN')) {
            $attendance = $attendanceRepository->findBy([], ['id' => 'DESC']);
        } else {
            $employee = $user?->getEmployee();
            $attendance = $employee ? $attendanceRepository->findBy(['employee' => $employee], ['id' => 'DESC']) : [];
        }
        return $this->render('attendance/index.html.twig', [
            'attendances' => $attendance,
        ]);
    }

    #[Route('/new', name: 'app_attendance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $attendance = new Attendance();
        $form = $this->createForm(AttendanceType::class, $attendance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($attendance);
            $entityManager->flush();
            $this->addFlash('success', 'Attendance record added successfully!');

            return $this->redirectToRoute('app_attendance_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Please fix the errors in the attendance form.');
        }

        return $this->render('attendance/new.html.twig', [
            'attendance' => $attendance,
            'form' => $form,
        ]);
    }

    #[Route('/check-in', name: 'app_attendance', methods: ['POST'])]
    public function checkIn(
        EntityManagerInterface $entityManager,
        AttendanceRepository $attendanceRepository
    ): Response {
        /** @var User|null $user */
        $user = $this->getUser();
        $employee = $user?->getEmployee();

        if (!$employee) {
            $this->addFlash('danger', 'No employee profile linked to your user account.');
            return $this->redirectToRoute('app_employee_deshboard');
        }

        $todayAttendance = $attendanceRepository->findattendance($employee);
        
        if (!$todayAttendance) {
            $attendance = new Attendance();
            $attendance->setDate((new \DateTime())->format('Y-m-d'));
            $attendance->setCheckIn((new \DateTime())->format('H:i:s'));
            $attendance->setStatus("present");
            $attendance->setEmployee($employee);
            $entityManager->persist($attendance);
            $entityManager->flush();
            $this->addFlash('success', 'Checked in successfully!');
        } else {
            $this->addFlash('info', 'You have already checked in today.');
        }

        return $this->redirectToRoute('app_employee_deshboard', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/check-out', name: 'app_attendance_checkout', methods: ['POST'])]
    public function checkOut(
        EntityManagerInterface $entityManagerInterface,
        AttendanceRepository $attendanceRepository,
        EventDispatcherInterface $dispatcher
    ): Response {
        /** @var User|null $user */
        $user = $this->getUser();
        $employee = $user?->getEmployee();

        if (!$employee) {
            $this->addFlash('danger', 'No employee profile linked to your user account.');
            return $this->redirectToRoute('app_employee_deshboard');
        }

        $todayAttendance = $attendanceRepository->findattendance($employee);
        
        if (!$todayAttendance) {
            $this->addFlash('danger', 'You must check in first before checking out today.');
        } elseif (!$todayAttendance->getCheckOut()) {
            $todayAttendance->setCheckOut((new \DateTime())->format('H:i:s'));
            $dispatcher->dispatch(new AttendanceCheckedOutEvent($todayAttendance));
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Checked out successfully!');
        } else {
            $this->addFlash('info', 'You have already checked out today.');
        }

        return $this->redirectToRoute('app_employee_deshboard', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_attendance_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Attendance $attendance, AttendanceRepository $attendanceRepository): Response
    {
        /** @var User|null $currentUser */
        $currentUser = $this->getUser();
        $employee = $attendance->getEmployee();

        // HR & Admin can view any attendance details. Regular employees can only view their own.
        if (!$this->isGranted('ROLE_HR') && !$this->isGranted('ROLE_ADMIN') && $employee && $employee->getUser() !== $currentUser) {
            $this->addFlash('danger', 'You can only view your own attendance details.');
            return $this->redirectToRoute('app_attendance_index');
        }

        $attendances = $employee ? $attendanceRepository->findBy(['employee' => $employee], ['id' => 'DESC']) : [$attendance];

        $presentCount = 0;
        $absentCount = 0;
        $lateCount = 0;

        foreach ($attendances as $att) {
            $st = strtolower((string)$att->getStatus());
            if ($st === 'present') {
                $presentCount++;
            } elseif ($st === 'absent') {
                $absentCount++;
            } elseif ($st === 'late') {
                $lateCount++;
            }
        }

        return $this->render('attendance/show.html.twig', [
            'attendance'     => $attendance,
            'targetEmployee' => $employee,
            'attendances'    => $attendances,
            'presentCount'   => $presentCount,
            'absentCount'    => $absentCount,
            'lateCount'      => $lateCount,
            'totalCount'     => count($attendances),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_attendance_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Attendance $attendance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AttendanceType::class, $attendance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Attendance record updated!');

            return $this->redirectToRoute('app_attendance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('attendance/edit.html.twig', [
            'attendance' => $attendance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_attendance_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Attendance $attendance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$attendance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($attendance);
            $entityManager->flush();
            $this->addFlash('warning', 'Attendance record deleted.');
        }

        return $this->redirectToRoute('app_attendance_index', [], Response::HTTP_SEE_OTHER);
    }
}
