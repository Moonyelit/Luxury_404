<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompagnyController extends AbstractController
{

    
    #[Route('/compagny', name: 'app_compagny')]
    public function compagny(): Response
    {
        return $this->render('/compagny.html.twig');
    }

}