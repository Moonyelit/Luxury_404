<?php

namespace App\Controller\Admin;

use App\Entity\Candidate;
use App\Entity\Gender;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;

class CandidateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Candidate::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Crud::PAGE_NEW);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            IdField::new('user.id')->hideOnForm(),
            AssociationField::new('gender')
                ->setCrudController(GenderCrudController::class)
                ->setFormTypeOption('choice_label', 'name'),
            TextField::new('firstName'),
            TextField::new('lastName'),            
            DateTimeField::new('birthDate'),            
            TextField::new('profilePicture'),
            IntegerField::new('completionPercentage'),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm(),
            DateTimeField::new('deletedAt')->hideOnForm(),
            TextField::new('currentLocation'),
            TextField::new('address'),
            TextField::new('country'),
            TextField::new('nationality'),
            TextField::new('birthplace'),
            AssociationField::new('experience')
            ->setCrudController(ExperienceCrudController::class)
            ->setFormTypeOption('choice_label', 'time'),
            AssociationField::new('jobCategory')
            ->setCrudController(JobCategoryCrudController::class)
            ->setFormTypeOption('choice_label', 'name'),
            TextField::new('description'),
            TextField::new('passport'),
            TextField::new('CV'),


        ];
    }
}