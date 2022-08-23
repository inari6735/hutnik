<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InformationController extends AbstractController
{
    #[Route('/information', name: 'app_information')]
    public function getInformations(): Response
    {
        return $this->render('information/information.html.twig');
    }
}
