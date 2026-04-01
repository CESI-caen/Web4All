<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class OffreController extends AbstractController
{


    #[Route('/mes-offres', name: 'MesOffres')]
    public function mesOffres(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        return $this->render('home/mes-offres.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute,]);
    }

    #[Route('/offre', name: 'Offre')]    //#[Route('/offre/{id}', name: 'AfficherOffre')]
                                            //#[Route('/api/check-cv', name: 'CheckCv', methods: ['POST'])]
                                            //#[Route('/offre/{id}/postuler', name: 'PostulerOffre')]
    public function afficherOffre(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route');
        return $this->render('home/offre-detail.html.twig', ['ancien_page_title' => 'Home','page_title' => $currentRoute,]);
    }

    #[Route('/avis', name: 'Avis')]  // Avis d'une offre
    public function avis(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route');
        return $this->render('home/avis-offre.html.twig', ['ancien_page_title' => 'Offre','page_title' => $currentRoute,]);
    }
}
