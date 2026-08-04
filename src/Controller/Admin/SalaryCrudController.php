<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Salary;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SalaryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Salary::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('employee', 'Employee'),
            TextField::new('month', 'Month'),
            TextField::new('year', 'Year'),
            TextField::new('basicSalary', 'Basic Salary'),
            TextField::new('bonus', 'Bonus'),
            TextField::new('deduction', 'Deduction'),
            TextField::new('netSalary', 'Net Salary'),
            ChoiceField::new('paymentStatus', 'Payment Status')->setChoices([
                'Paid'    => 'Paid',
                'Pending' => 'Pending',
                'Failed'  => 'Failed',
            ]),
        ];
    }
}
