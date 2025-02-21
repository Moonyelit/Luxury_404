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

        $allJobs = $jobOfferRepository->findBy([], ['id' => 'ASC']);
        $currentJobIndex = array_search($job, $allJobs);
        $previousJob = $currentJobIndex > 0 ? $allJobs[$currentJobIndex - 1] : null;
        $nextJob = $currentJobIndex < count($allJobs) - 1 ? $allJobs[$currentJobIndex + 1] : null;

        return $this->render('jobs/show.html.twig', [
            'jobCategories' => $jobCategories,
            'job' => $job,
            'previousJob' => $previousJob,
            'nextJob' => $nextJob,
        ]);
    }
}
