<?php

namespace App\Controller\Admin;

use App\Entity\JobOffer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class JobOfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JobOffer::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('recruiter')
            ->setCrudController(RecruiterCrudController::class)
            ->setFormTypeOption('choice_label', function ($recruiter) {
                $contactName = $recruiter->getContactName() ?: 'N/A';
                $societyName = $recruiter->getSocietyName() ?: 'N/A';
                return sprintf('%d - %s - %s', $recruiter->getId(), $contactName, $societyName);
            }),
            AssociationField::new('jobCategory')->setCrudController(JobCategoryCrudController::class)->setFormTypeOption('choice_label', 'name'),
            // IntegerField::new('reference'),
            TextField::new('description'),
            BooleanField::new('isActivate'),
            TextField::new('notes'),
            TextField::new('address'),
            TextField::new('country'),
            DateTimeField::new('dateStart'),
            DateTimeField::new('dateClosing'),
            IntegerField::new('salary'),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updateAt')->hideOnForm(),
            TextField::new('slug')->hideOnForm(),
        ];
    }
}
