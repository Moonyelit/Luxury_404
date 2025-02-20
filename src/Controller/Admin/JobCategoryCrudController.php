<?php

namespace App\Controller\Admin;

use App\Entity\JobCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class JobCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JobCategory::class;
    }

    
}
