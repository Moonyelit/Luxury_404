<?php

namespace App\Controller\Admin;

use App\Entity\Recruiter;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/admin')]
class AdminController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    #[Route('/manage-roles/{id}', name: 'admin_manage_roles')]
    public function manageRoles(User $user, Request $request): Response
    {
        // Vérifie si le token CSRF est valide
        if ($this->isCsrfTokenValid('update_roles', $request->request->get('_token'))) {
            $roles = $user->getRoles();
            $action = $request->request->get('action');

            // Gère les différentes actions pour promouvoir ou rétrograder les rôles
            if ($action === 'promote_candidate') {
                $roles[] = 'ROLE_CANDIDATE';
            } elseif ($action === 'demote_candidate') {
                $roles = array_diff($roles, ['ROLE_CANDIDATE']);
                $this->sendRejectionEmail($user, $request->request->get('invalid_field'));
            } elseif ($action === 'promote_recruiter') {
                if (!in_array('ROLE_RECRUITER', $roles)) {
                    $roles[] = 'ROLE_RECRUITER';
                    $this->createRecruiterForUser($user);
                }
            } elseif ($action === 'demote_recruiter') {
                $roles = array_diff($roles, ['ROLE_RECRUITER']);
                $this->removeRecruiterForUser($user);
            }

            // Met à jour les rôles de l'utilisateur et enregistre les modifications
            $user->setRoles(array_unique($roles));
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_dashboard_index');
    }

    // Crée un recruteur pour l'utilisateur donné
    private function createRecruiterForUser(User $user): void
    {
        $recruiter = new Recruiter();
        $recruiter->setUser($user);
        $recruiter->setEmail($user->getEmail());
        $recruiter->setDateCreated(new \DateTimeImmutable());

        dd("createRecruiterForUser called", $user);

        $this->entityManager->persist($recruiter);
        $this->entityManager->flush();
    }

    // Supprime le recruteur associé à l'utilisateur donné
    private function removeRecruiterForUser(User $user): void
    {
        $recruiter = $this->entityManager->getRepository(Recruiter::class)->findOneBy(['user' => $user]);

        if ($recruiter) {
            $this->entityManager->remove($recruiter);
            $this->entityManager->flush();
        }
    }

    // Envoie un email de rejet à l'utilisateur
    private function sendRejectionEmail(User $user, string $invalidField): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@yourdomain.com')
            ->to($user->getEmail())
            ->subject('Profile Incomplete - Document Issue')
            ->htmlTemplate('emails/rejection_email.html.twig')
            ->context([
                'user' => $user,
                'invalid_field' => $invalidField
            ]);

        $this->mailer->send($email);
    }
}
