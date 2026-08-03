<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AttendanceRepository;
use App\Repository\EmployeeRepository;
use App\Repository\LeaveRequestRepository;
use App\Repository\SalaryRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_EMPLOYEE')]
class EmployeeDeshboardController extends AbstractController
{
    #[Route('/employee/deshboard', name: 'app_employee_deshboard')]
    public function index(
        EmployeeRepository $employeeRepository,
        LeaveRequestRepository $leaveRequestRepository,
        AttendanceRepository $attendanceRepository,
        TaskRepository $taskRepository,
        SalaryRepository $salaryRepository
    ): Response {
        /** @var User|null $user */
        $user = $this->getUser();
        $employee = $employeeRepository->findOneBy(['user' => $user]);

        $todayAttendance = $employee ? $attendanceRepository->findattendance($employee) : null;
        $leaveRequests = $employee ? $leaveRequestRepository->findBy(['employee' => $employee], ['id' => 'DESC']) : [];
        $attendances = $employee ? $attendanceRepository->findBy(['employee' => $employee], ['id' => 'DESC']) : [];
        $tasks = $employee ? $taskRepository->findByEmployee($employee) : [];
        $salaries = $employee ? $salaryRepository->findBy(['employee' => $employee], ['id' => 'DESC']) : [];

        return $this->render('employee_deshboard/index.html.twig', [
            'employee'        => $employee,
            'todayAttendance' => $todayAttendance,
            'leaveRequests'   => $leaveRequests,
            'attendances'     => $attendances,
            'tasks'           => $tasks,
            'salaries'        => $salaries,
        ]);
    }
}
