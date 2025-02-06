<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }


    // #[Route('/login', name: 'app_login')]
    // public function login(): Response
    // {
    //     return $this->render('auth/login.html.twig');
    // }


    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('auth/profile.html.twig');
    }

    // #[Route('/register', name: 'app_register')]
    // public function register(): Response
    // {
    //     return $this->render('auth/register.html.twig');
    // }

    #[Route('/jobs/index', name: 'app_jobsIndex')]
    public function jobsIndex(): Response
    {
        return $this->render('jobs/index.html.twig');
    }

    #[Route('/jobs/show', name: 'app_jobsShow')]
    public function jobsShow(): Response
    {
        return $this->render('jobs/show.html.twig');
    }

    #[Route('/compagny', name: 'app_compagny')]
    public function compagny(): Response
    {
        return $this->render('/compagny.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }
}