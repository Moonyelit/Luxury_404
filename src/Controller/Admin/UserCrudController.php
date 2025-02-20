<?php

namespace App\Controller\Admin;

use App\Entity\Recruiter;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Crud::PAGE_NEW);
    }

    // quand on crée un user
    // public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    // {

    //     if ($entityInstance instanceof User) {
            

    //         $roles = $entityInstance->getRoles();

    //         if (in_array('ROLE_RECRUITER', $roles)) {
    //             $recruiter = new Recruiter();
    //             $recruiter->setUser($entityInstance);

    //             $entityManager->persist($recruiter);
    //         }


    //     }


    //     $entityManager->persist($entityInstance);
    //     $entityManager->flush();
    // }

    // quand on update un user
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
{
    if ($entityInstance instanceof User) {
        $roles = $entityInstance->getRoles();

        if (in_array('ROLE_RECRUITER', $roles)) {
            $recruiterRepository = $entityManager->getRepository(Recruiter::class);
            $existingRecruiter = $recruiterRepository->findOneBy(['user' => $entityInstance]);

            if (!$existingRecruiter) {
                $recruiter = new Recruiter();
                $recruiter->setUser($entityInstance);
                $entityManager->persist($recruiter);
            }
        }
    }

    $entityManager->persist($entityInstance);
    $entityManager->flush();
}




    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email'),
            ChoiceField::new('roles')
                ->setChoices([
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    'Candidate' => 'ROLE_CANDIDATE',
                    'Recruiter' => 'ROLE_RECRUITER',
                ])
                ->allowMultipleChoices(),
            BooleanField::new('isVerified'),
        ];

        if ($pageName === Crud::PAGE_EDIT){
            $fields[] = TextField::new('password') ->onlyOnForms();
        }

        return $fields;
    }
}