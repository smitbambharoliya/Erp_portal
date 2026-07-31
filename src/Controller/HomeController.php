<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    // Step 1: /home URL hit thay -> index() method call thay
    // Step 2: $user = hu currently login chhu (User Entity object)
    // Step 3: $employee = database mathi maro Employee record lau
    //         (Employee table ma 'user' column employee ni User sthe jodale che)
    // Step 4: $employee ne template ne pathav (template ma vapat aavshe)

    #[Route('/home', name: 'app_home')]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        // Login thayelo user object
        $user = $this->getUser();

        // Employee table mathi e user linked employee record shodo
        // 'user' = Employee entity no 'user' property (OneToOne relation saathe)
        $employee = $employeeRepository->findOneBy([
            'user' => $user,
        ]);

        // Template ne render karo ane $employee passav
        return $this->render('home/index.html.twig', [
            'employee' => $employee,
        ]);
    }
}
