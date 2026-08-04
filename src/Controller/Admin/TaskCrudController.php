<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Task;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TaskCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Task::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Task Title'),
            TextareaField::new('description', 'Description'),
            TextField::new('deadline', 'Deadline'),
            ChoiceField::new('priority', 'Priority')->setChoices([
                'Low'    => 'Low',
                'Medium' => 'Medium',
                'High'   => 'High',
                'Urgent' => 'Urgent',
            ]),
            ChoiceField::new('status', 'Status')->setChoices([
                'Pending'     => 'Pending',
                'In Progress' => 'In Progress',
                'Completed'   => 'Completed',
                'Cancelled'   => 'Cancelled',
            ]),
        ];
    }
}
