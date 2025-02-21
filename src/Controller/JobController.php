<?php

namespace App\Controller;

use App\Repository\JobCategoryRepository;
use App\Repository\JobOfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    #[Route('/jobs/index', name: 'app_jobsIndex')]
    public function jobsIndex(JobCategoryRepository $jobCategoryRepository, JobOfferRepository $jobOfferRepository): Response
    {
        $jobCategories = $jobCategoryRepository->findAll();
        $jobOffers = $jobOfferRepository->findAll();

        return $this->render('jobs/index.html.twig', [
            'jobCategories' => $jobCategories,
            'jobOffers' => $jobOffers,
        ]);
    }

    #[Route('/jobs/show/{slug}', name: 'app_jobsShow')]
    public function jobsShow(string $slug, JobCategoryRepository $jobCategoryRepository, JobOfferRepository $jobOfferRepository): Response
    {
        $jobCategories = $jobCategoryRepository->findAll();
        $job = $jobOfferRepository->findOneBy(['slug' => $slug]);

        if (!$job) {
            throw $this->createNotFoundException('The job does not exist');
        }

        return $this->render('jobs/show.html.twig', [
            'jobCategories' => $jobCategories,
            'job' => $job,
        ]);
    }
}