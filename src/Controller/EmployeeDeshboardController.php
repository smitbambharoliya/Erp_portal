<?php


namespace App\Controller;

use App\Repository\AttendanceRepository;
use App\Repository\EmployeeRepository;
use App\Repository\LeaveRequestRepository;
use App\Repository\ProjectRepository;
use App\Repository\SalaryRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class EmployeeDeshboardController extends AbstractController
{
    #[Route('/employee/deshboard', name: 'app_employee_deshboard')]
    #[IsGranted('ROLE_EMPLOYEE')]
    public function index(EmployeeRepository $employeeRepository,
    LeaveRequestRepository $leaveRequestRepository,
    AttendanceRepository $attendanceRepository,
    TaskRepository $taskRepository,
    ProjectRepository $projectRepository,
    SalaryRepository $salaryRepository): Response
    {
        $user = $this->getUser();
        

        $employee = $employeeRepository->findOneBy(['user' => $user]);
        $todayAttendance = $attendanceRepository->findattendance($employee);
        $leaveRequests = $leaveRequestRepository->findBy(['employee' => $employee]);
        $attendances = $attendanceRepository->findBy(['employee' => $employee], ['id' => 'DESC']);
        $tasks = $taskRepository->findByEmployee($employee);
        $projects = $projectRepository->findAll();
        $salaries = $salaryRepository->findBy(['employee' => $employee]);

        return $this->render('employee_deshboard/index.html.twig', [
            'controller_name' => 'EmployeeDeshboardController',
            'employee' => $employee,
            'todayAttendance' => $todayAttendance,
            'leaveRequests' => $leaveRequests,
            'attendances' => $attendances,
            'tasks' => $tasks,
            'projects' => $projects,
            'salaries' => $salaries,
        ]);
    }
}
