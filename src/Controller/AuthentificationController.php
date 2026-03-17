<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthentificationController extends AbstractController
{
    #[Route('/login', name: 'Login')]
    public function sign_in(): Response
    {
        return $this->render('authentification/login.html.twig');
    }

    #[Route('/create-account', name: 'create-account')]
    public function sign_up(Request $request): Response
    {
        $page = $request->request->get('page', 1);
        $email = $request->request->get('email', "");
        return $this->render('authentification/create-account.html.twig', ['page' => $page, 'email' => $email]);
    }
}
