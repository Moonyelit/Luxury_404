<?php
// src/Controller/ProfileController.php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\User;
use App\Form\CandidateType;
use App\Form\UpdatePassword;
use App\Service\FileUploader;
use App\Service\ProfileCompletionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

final class ProfileController extends AbstractController
{
    private ProfileCompletionService $profileCompletionService;

    public function __construct(ProfileCompletionService $profileCompletionService)
    {
        $this->profileCompletionService = $profileCompletionService;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(
        EntityManagerInterface $entityManager,
        Request $request,
        FileUploader $fileUploader
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $candidate = $user->getCandidate();

        if (!$candidate) {
            $candidate = new Candidate();
            $candidate->setUser($user);
            $entityManager->persist($candidate);
            $entityManager->flush();
        }

        if (!$user->isVerified()) {
            return $this->render('errors/not-verified.html.twig');
        }

        // Formulaire Candidate
        $formCandidate = $this->createForm(CandidateType::class, $candidate);
        $formCandidate->handleRequest($request);

        if ($formCandidate->isSubmitted() && $formCandidate->isValid()) {
            // Gestion des fichiers
            $profilePictureFile = $formCandidate->get('profilePictureFile')->getData();
            $passportFile = $formCandidate->get('passportFile')->getData();
            $CVFile = $formCandidate->get('CVFile')->getData();

            if ($profilePictureFile) {
                $profilePictureName = $fileUploader->upload($profilePictureFile, $candidate, 'profilePicture', 'profile_pictures');
                $candidate->setProfilePicture($profilePictureName);
            }

            if ($passportFile) {
                $passportFileName = $fileUploader->upload($passportFile, $candidate, 'passport', 'passports');
                $candidate->setPassport($passportFileName);
            }

            if ($CVFile) {
                $CVName = $fileUploader->upload($CVFile, $candidate, 'CV', 'CVS');
                $candidate->setCV($CVName);
            }

            $this->profileCompletionService->updateProfileCompletion($candidate);

            $entityManager->persist($candidate);
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated successfully');
        }

        // Formulaire de mise à jour du mot de passe
        $formPassword = $this->createForm(UpdatePassword::class);
        // Le traitement du formPassword se fait dans la méthode updatePassword

        $completion = $this->profileCompletionService->calculateCompletionPercentage($candidate);

        return $this->render('profile/profile.html.twig', [
            'formCandidate' => $formCandidate->createView(),
            'formPassword'  => $formPassword->createView(),
            'candidate'     => $candidate,
            'completion'    => $completion,
        ]);
    }

    #[Route('/profile/update-password', name: 'app_profile_update_password', methods: ['POST'])]
    public function updatePassword(
        EntityManagerInterface $entityManager,
        Request $request,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $formPassword = $this->createForm(UpdatePassword::class);
        $formPassword->handleRequest($request);

        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $data = $formPassword->getData();
            $email = $data['email'];
            $newPassword = $data['password'];
            $passwordRepeat = $data['password_repeat'];

            if ($newPassword !== $passwordRepeat) {
                $this->addFlash('error', 'Passwords do not match.');
                return $this->redirectToRoute('app_profile');
            }

            if ($email !== $user->getEmail()) {
                $this->addFlash('error', 'Email does not match.');
                return $this->redirectToRoute('app_profile');
            }

            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);
            $entityManager->flush();

            $this->addFlash('success', 'Password updated successfully');
            return $this->redirectToRoute('app_profile');
        }

        $candidate = $user->getCandidate();
        $completion = $this->profileCompletionService->calculateCompletionPercentage($candidate);

        return $this->render('profile/profile.html.twig', [
            'formCandidate' => $this->createForm(CandidateType::class, $candidate)->createView(),
            'formPassword'  => $formPassword->createView(),
            'candidate'     => $candidate,
            'completion'    => $completion,
        ]);
    }






}