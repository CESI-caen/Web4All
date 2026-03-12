<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthentificationController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function sign_in(): Response
    {
        return $this->render('authentification/login.html.twig');
    }

    #[Route('/sign-in', name: 'app_sign_in')]
    public function sign_up(): Response
    {
        return $this->render('authentification/sign_in.html.twig');
    }
}
