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
        yield MenuItem::linkToCrud('Employees', 'fas fa-users', \App\Entity\Employee::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-user-circle', \App\Entity\User::class);
        yield MenuItem::linkToCrud('Departments', 'fas fa-building', \App\Entity\Department::class);

        yield MenuItem::section('Work');
        yield MenuItem::linkToCrud('Projects', 'fas fa-diagram-project', \App\Entity\Project::class);
        yield MenuItem::linkToCrud('Tasks', 'fas fa-list-check', \App\Entity\Task::class);

        yield MenuItem::section('HR');
        yield MenuItem::linkToCrud('Leave Requests', 'fas fa-calendar-xmark', \App\Entity\LeaveRequest::class);
        yield MenuItem::linkToCrud('Salary', 'fas fa-money-bill-wave', \App\Entity\Salary::class);
        yield MenuItem::linkToCrud('Announcements', 'fas fa-bullhorn', \App\Entity\Announcement::class);
        yield MenuItem::linkToCrud('Holidays', 'fas fa-umbrella-beach', \App\Entity\Holiday::class);
        yield MenuItem::linkToCrud('Documents', 'fas fa-folder-open', \App\Entity\Document::class);
        yield MenuItem::linkToCrud('Notifications', 'fas fa-bell', \App\Entity\Notification::class);

        yield MenuItem::section('Site');
        yield MenuItem::linkToUrl('Back to Portal', 'fas fa-arrow-left', '/home');
        yield MenuItem::linkToLogout('Logout', 'fas fa-sign-out-alt');
    }
}
