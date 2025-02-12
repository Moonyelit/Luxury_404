<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // Vous pouvez configurer les champs ici si nécessaire
    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         // Champs à configurer
    //     ];
    // }
}