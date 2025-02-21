<?php
namespace App\Controller;

use App\Repository\JobCategoryRepository;
use App\Repository\JobOfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(JobCategoryRepository $jobCategoryRepository, JobOfferRepository $jobOfferRepository): Response
    {
        $jobCategories = $jobCategoryRepository->findAll();
        $jobOffers = $jobOfferRepository->findAll();


        return $this->render('index.html.twig', [
            'jobCategories' => $jobCategories,
            'jobOffers' => $jobOffers,
        ]);
        
    }







}