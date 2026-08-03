<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AttendanceRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use App\Repository\LeaveRequestRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_HR')]
final class HrDashboardController extends AbstractController
{
    #[Route('/hr/dashboard', name: 'app_hr_dashboard')]
    public function index(
        EmployeeRepository $employeeRepository,
        DepartmentRepository $departmentRepository,
        LeaveRequestRepository $leaveRequestRepository,
        AttendanceRepository $attendanceRepository,
        ProjectRepository $projectRepository
    ): Response {
        $departments = $departmentRepository->findAll();
        $totalEmployees = count($employeeRepository->findAll());
        $activeEmployees = count($employeeRepository->findBy(['status' => 'Active']));
        $pendingLeavesList = $leaveRequestRepository->findBy(['status' => 'Pending'], ['id' => 'DESC']);
        $todayDate = (new \DateTime())->format('Y-m-d');
        $todayAttendanceList = $attendanceRepository->findBy(['date' => $todayDate]);
        $totalProjects = count($projectRepository->findAll());

        return $this->render('hr/dashboard.html.twig', [
            'total_employees'       => $totalEmployees,
            'active_employees'      => $activeEmployees,
            'departments_count'     => count($departments),
            'pending_leaves_count'  => count($pendingLeavesList),
            'pending_leaves_list'   => $pendingLeavesList,
            'today_attendance_count'=> count($todayAttendanceList),
            'today_attendance_list' => $todayAttendanceList,
            'total_projects'        => $totalProjects,
        ]);
    }
}
