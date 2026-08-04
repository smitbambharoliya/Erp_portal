<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\LeaveRequest;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LeaveRequestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LeaveRequest::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('employee', 'Employee'),
            ChoiceField::new('leaveType', 'Leave Type')->setChoices([
                'Casual Leave'   => 'Casual Leave',
                'Sick Leave'     => 'Sick Leave',
                'Annual Leave'   => 'Annual Leave',
                'Maternity Leave'=> 'Maternity Leave',
                'Emergency Leave'=> 'Emergency Leave',
            ]),
            TextField::new('startDate', 'Start Date'),
            TextField::new('endDate', 'End Date'),
            ChoiceField::new('status', 'Status')->setChoices([
                'Pending'  => 'Pending',
                'Approved' => 'Approved',
                'Rejected' => 'Rejected',
            ]),
        ];
    }
}
