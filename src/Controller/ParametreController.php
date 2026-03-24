<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ParametreController extends AbstractController
{
    #[Route('/parametres', name: 'Paramètres')]   // Route pour la page de paramètres
    public function settings(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        return $this->render('parametre/index.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute,]);  // 'ancien_page_title' => 'Home'  <-- non dynamique
    }
}
