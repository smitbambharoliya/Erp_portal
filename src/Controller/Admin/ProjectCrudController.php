<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Project Name'),
            TextareaField::new('description', 'Description'),
            TextField::new('startDate', 'Start Date'),
            TextField::new('endDate', 'End Date'),
            TextField::new('budget', 'Budget'),
            ChoiceField::new('status', 'Status')->setChoices([
                'Pending'     => 'Pending',
                'In Progress' => 'In Progress',
                'Completed'   => 'Completed',
                'On Hold'     => 'On Hold',
            ]),
        ];
    }
}
