<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthentificationController extends AbstractController
{
    #[Route('/login', name: 'Login')]
    public function sign_in(): Response
    {
        return $this->render('authentification/login.html.twig');
    }

    #[Route('/create-account', name: 'CreateAccount')]
    public function sign_up(): Response
    {
        return $this->render('authentification/create-account.html.twig');
    }
}
