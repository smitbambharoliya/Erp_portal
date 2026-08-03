<?php

namespace App\Controller\Admin;

use App\Controller\AttendanceController;
use App\Controller\DepartmentController;
use App\Controller\LeaveRequestController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // return $this->redirectToRoute('admin_user_index');

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Erp Portal');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkTo(UserCrudController::class, 'Users', 'fas fa-list');
        yield MenuItem::linkTo(DepartmentCrudController::class, 'Departments', 'fas fa-list');
        yield MenuItem::linkTo(LeaveRequestCrudController::class, 'Leaves', 'fas fa-list');
        yield MenuItem::linkTo(HolidayCrudController::class, 'Holiday', 'fas fa-list');
        yield MenuItem::linkTo(EmployeeCrudController::class, 'Employees', 'fas fa-list');
        yield MenuItem::linkTo(HolidayCrudController::class, 'Holiday', 'fas fa-list');
        yield MenuItem::linkTo(DocumentCrudController::class, 'Documents', 'fas fa-list');
        yield MenuItem::linkTo(NotificationCrudController::class, 'Notifications', 'fas fa-list');
        yield MenuItem::linkTo(ProjectCrudController::class, 'Projects', 'fas fa-list');
        yield MenuItem::linkTo(TaskCrudController::class, 'Tasks', 'fas fa-list');
        yield MenuItem::linkTo(SalaryCrudController::class,'Salary','fas fa-list');
        yield MenuItem::linkTo(UserCrudController::class,'Users','fas fa-list');
        
    }
}
