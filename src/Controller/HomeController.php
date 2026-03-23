<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'Home')]  // Route pour la page d'accueil
    
    public function index(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        return $this->render('home/index.html.twig',['ancien_page_title' => 'null','page_title' => $currentRoute,]); // Utiliser les names des routes pour les liens --> {{ path('Home') }}
    }

    #[Route('/parametres', name: 'Paramètres')]   // Route pour la page de paramètres
    public function settings(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        return $this->render('home/settings.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute,]);  // 'ancien_page_title' => 'Home'  <-- non dynamique
    }

    #[Route('/cv', name: 'Fichier Personnels')]  // Route pour la page de documents
    public function document(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        return $this->render('home/cv.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute,]);    // 'ancien_page_title' => 'Home'  <-- non dynamique
    }

    #[Route('/upload', name: 'upload', methods: ['POST'])]    // Route pour gérer l'upload de fichiers
    public function upload(Request $request): Response
    {
        $file = $request->files->get('file');

        if ($file) {

                /* Exemple de conditions à ajouter pour limiter les types de fichiers et la taille (non fonctionnel pour le moment, à revoir) :

            $allowedTypes = ['application/pdf','image/png','image/jpeg'];

            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return new Response("Type de fichier interdit");
            }

            if ($file->getSize() > 2000000) {
                return new Response("Fichier trop lourd");
            }
            */

            $file->move(
                $this->getParameter('kernel.project_dir').'/public/uploads',
                $file->getClientOriginalName()
            );
        }

        return $this->redirectToRoute('Fichier Personnels');
    }
}
