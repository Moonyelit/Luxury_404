<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\User;
use App\Form\CandidateType;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(EntityManagerInterface $entityManager, Request $request, FileUploader $fileUploader, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var User */
        $user = $this->getUser();

        $candidate = $user->getCandidate();

        if(!$candidate){
            $candidate = new Candidate();
            $candidate->setUser($user);
            $entityManager->persist($candidate);
            $entityManager->flush();
        }

        if(!$user->isVerified())
        {
            return $this->render('errors/not-verified.html.twig', [
            
            ]);
        }

        // Formulaire de mise à jour du profil
        $formCandidate = $this->createForm(CandidateType::class, $candidate);
        $formCandidate->handleRequest($request);
    
        if ($formCandidate->isSubmitted() && $formCandidate->isValid()) {
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
            
            $entityManager->persist($candidate);
            $entityManager->flush();
    
            $this->addFlash('success', 'Profile updated successfully');
        }

        // Formulaire de changement de mot de passe
        $formPassword = $this->createForm(ChangePasswordType::class);
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

        return $this->render('profile/profile.html.twig', [
            'formCandidate' => $formCandidate->createView(),
            'formPassword' => $formPassword->createView(),
            'candidate' => $candidate,
        ]);
    }




    // #[Route('/profile/delete', name: 'app_profile_delete', methods: ['POST'])]
    // public function deleteAccount(EntityManagerInterface $entityManager): Response
    // {
    //     /** @var User */
    //     $user = $this->getUser();
    //     $candidate = $user->getCandidate();

    //     if ($candidate) {
    //         $entityManager->remove($candidate);
    //     }

    //     $entityManager->remove($user);
    //     $entityManager->flush();

    //     $this->addFlash('success', 'Your account has been deleted successfully.');

    //     return $this->redirectToRoute('app_home');
    // }
}