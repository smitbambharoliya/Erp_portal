<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Holiday;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HolidayCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Holiday::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Holiday Name'),
            TextField::new('holidayDate', 'Date'),
            TextareaField::new('description', 'Description'),
        ];
    }
}
