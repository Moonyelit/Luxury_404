<?php
namespace App\Service;

use App\Entity\Candidate;
use Doctrine\ORM\EntityManagerInterface;

class ProfileCompletionService
{   

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateProfileCompletion(Candidate $candidate): void
    {
        $completionPercentage = $this->calculateCompletionPercentage($candidate);
        $isCandidate = ($completionPercentage === 100);

        dd($completionPercentage, $isCandidate);

        $candidate->setCompletionPercentage($completionPercentage);
        $candidate->setIsCandidate($isCandidate);

        $this->entityManager->persist($candidate);
        $this->entityManager->flush();
    }

    public function calculateCompletionPercentage(Candidate $candidate): int
    {
        $requiredFields = [
            'firstName',
            'profilePicture',
            'gender',
            'lastName',
            'address',
            'country',
            'nationality',
            'birthdate',
            'experience',
            'jobCategory',
            'passport',
            'CV'
        ];

        $totalRequired = count($requiredFields);
        $filledCount = 0;

        foreach ($requiredFields as $field) {
            $getter = 'get' . ucfirst($field);
            if (method_exists($candidate, $getter)) {
                $value = $candidate->$getter();
                if (!empty($value)) {
                    $filledCount++;
                }
            }
        }

        return (int) round(($filledCount / $totalRequired) * 100);
    }


}