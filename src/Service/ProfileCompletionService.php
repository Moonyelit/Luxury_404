<?php

namespace App\Service;

use App\Entity\Candidate;

class ProfileCompletionService
{
    public function calculateCompletionPercentage(Candidate $candidate): int
    {
        // Liste des champs obligatoires pour la complétion
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

        // Calcul du pourcentage de complétion et retour d'un entier
        return (int) round(($filledCount / $totalRequired) * 100);
    }
}
