<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        // Redirect HR and Admin users to HR Dashboard
        if ($this->isGranted('ROLE_HR') || $this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_hr_dashboard');
        }

        // Redirect Regular Employees to Employee Workspace Dashboard
        return $this->redirectToRoute('app_employee_deshboard');
    }
}
