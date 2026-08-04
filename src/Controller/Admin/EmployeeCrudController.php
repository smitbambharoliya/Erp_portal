<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Employee;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EmployeeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Employee::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstName', 'First Name'),
            TextField::new('lastName', 'Last Name'),
            AssociationField::new('department', 'Department'),
            TextField::new('designation', 'Designation'),
            TextField::new('phone', 'Phone'),
            TextField::new('gender', 'Gender'),
            TextField::new('joiningDate', 'Joining Date'),
            ChoiceField::new('status', 'Status')->setChoices([
                'Active'     => 'Active',
                'Inactive'   => 'Inactive',
                'On Leave'   => 'On Leave',
            ]),
        ];
    }
}
