<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\AttendanceRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use App\Repository\LeaveRequestRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Controller\Admin\AnnouncementCrudController;
use App\Controller\Admin\DepartmentCrudController;
use App\Controller\Admin\DocumentCrudController;
use App\Controller\Admin\EmployeeCrudController;
use App\Controller\Admin\HolidayCrudController;
use App\Controller\Admin\LeaveRequestCrudController;
use App\Controller\Admin\NotificationCrudController;
use App\Controller\Admin\ProjectCrudController;
use App\Controller\Admin\SalaryCrudController;
use App\Controller\Admin\TaskCrudController;
use App\Controller\Admin\UserCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly UserRepository $userRepository,
        private readonly DepartmentRepository $departmentRepository,
        private readonly LeaveRequestRepository $leaveRequestRepository,
        private readonly ProjectRepository $projectRepository,
        private readonly TaskRepository $taskRepository,
        private readonly AttendanceRepository $attendanceRepository,
    ) {}

    public function index(): Response
    {
        $totalEmployees     = count($this->employeeRepository->findAll());
        $activeEmployees    = count($this->employeeRepository->findBy(['status' => 'Active']));
        $totalUsers         = count($this->userRepository->findAll());
        $totalDepartments   = count($this->departmentRepository->findAll());
        $totalProjects      = count($this->projectRepository->findAll());
        $totalTasks         = count($this->taskRepository->findAll());
        $pendingLeaves      = count($this->leaveRequestRepository->findBy(['status' => 'Pending']));
        $todayDate          = (new \DateTime())->format('Y-m-d');
        $todayAttendance    = count($this->attendanceRepository->findBy(['date' => $todayDate]));

        $recentLeaves       = $this->leaveRequestRepository->findBy(['status' => 'Pending'], ['id' => 'DESC'], 5);
        $recentEmployees    = $this->employeeRepository->findBy([], ['id' => 'DESC'], 5);

        return $this->render('admin/dashboard.html.twig', [
            'total_employees'    => $totalEmployees,
            'active_employees'   => $activeEmployees,
            'total_users'        => $totalUsers,
            'total_departments'  => $totalDepartments,
            'total_projects'     => $totalProjects,
            'total_tasks'        => $totalTasks,
            'pending_leaves'     => $pendingLeaves,
            'today_attendance'   => $todayAttendance,
            'recent_leaves'      => $recentLeaves,
            'recent_employees'   => $recentEmployees,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<span style="color:#5856d6;">⬡</span> ERP Admin')
            ->setFaviconPath('favicon.ico')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-gauge-high');

        yield MenuItem::section('People');
        yield MenuItem::linkTo(EmployeeCrudController::class, 'Employees', 'fas fa-users');
        yield MenuItem::linkTo(UserCrudController::class, 'Users', 'fas fa-user-circle');
        yield MenuItem::linkTo(DepartmentCrudController::class, 'Departments', 'fas fa-building');

        yield MenuItem::section('Work');
        yield MenuItem::linkTo(ProjectCrudController::class, 'Projects', 'fas fa-diagram-project');
        yield MenuItem::linkTo(TaskCrudController::class, 'Tasks', 'fas fa-list-check');

        yield MenuItem::section('HR');
        yield MenuItem::linkTo(LeaveRequestCrudController::class, 'Leave Requests', 'fas fa-calendar-xmark');
        yield MenuItem::linkTo(SalaryCrudController::class, 'Salary', 'fas fa-money-bill-wave');
        yield MenuItem::linkTo(AnnouncementCrudController::class, 'Announcements', 'fas fa-bullhorn');
        yield MenuItem::linkTo(HolidayCrudController::class, 'Holidays', 'fas fa-umbrella-beach');
        yield MenuItem::linkTo(DocumentCrudController::class, 'Documents', 'fas fa-folder-open');
        yield MenuItem::linkTo(NotificationCrudController::class, 'Notifications', 'fas fa-bell');

        yield MenuItem::section('Site');
        yield MenuItem::linkToUrl('Back to Portal', 'fas fa-arrow-left', '/home');
        yield MenuItem::linkToLogout('Logout', 'fas fa-sign-out-alt');
    }
}
