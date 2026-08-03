<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\AttendanceRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use App\Repository\LeaveRequestRepository;
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
    ): Response {

        $departments = $departmentRepository->findAll();

  
        $totalEmployees = count($employeeRepository->findAll());

        $pendingLeaves = $leaveRequestRepository->findBy(['status' => 'Pending']);

     
        $activeEmployees = $employeeRepository->findBy(['status' => 'Active']);

        return $this->render('hr/dashboard.html.twig', [
            'total_employees'     => $totalEmployees,
            'active_employees'    => count($activeEmployees),
            'departments'         => $departments,
            'pending_leaves'      => count($pendingLeaves),
            'pending_leaves_list' => $pendingLeaves,
        ]);
    }
}
