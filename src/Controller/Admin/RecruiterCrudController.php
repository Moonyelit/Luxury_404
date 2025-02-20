<?php

namespace App\Controller\Admin;

use App\Entity\Recruiter;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions; // Ajout de l'importation manquante
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud; // Ajout de l'importation manquante

class RecruiterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recruiter::class;
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
            AssociationField::new('user')
                ->setCrudController(UserCrudController::class)
                ->setFormTypeOption('choice_label', 'email')
                ->setFormTypeOption('query_builder', function ($repository) {
                    return $repository->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%ROLE_RECRUITER%');
                }),
            TextField::new('societyName'),
            TextField::new('activity'),
            TextField::new('contactName'),
            TextField::new('job'),
            TextField::new('phoneNumber'),
            TextField::new('email'),
            TextField::new('notes'),
            DateTimeField::new('dateCreated')->hideOnForm(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Recruiter) return;

        $user = $entityInstance->getUser();
        if ($user) {
            $roles = $user->getRoles();
            if (!in_array('ROLE_RECRUITER', $roles)) {
                $roles[] = 'ROLE_RECRUITER';
                $user->setRoles($roles);
            }
            $entityManager->persist($user);
            $entityManager->flush();
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
