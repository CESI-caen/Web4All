<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\PdoService;
use App\Model\OffreModel;

class OffreController extends AbstractController
{


    #[Route('/mes-offres', name: 'MesOffres')]
    public function mesOffres(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        return $this->render('offre/mes-offres.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user]);
    }

    //#[Route('/offre/{id}', name: 'AfficherOffre')]
    //#[Route('/api/check-cv', name: 'CheckCv', methods: ['POST'])]
    //#[Route('/offre/{id}/postuler', name: 'PostulerOffre')]

    #[Route('/offre/{id}', name: 'Offre')]
    public function afficherOffre(int $id, Request $request): Response
    {
        $pdo = new PdoService();
        $OffreModel = new OffreModel($pdo);

        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        $offre = $OffreModel->getOffreById($id);

        return $this->render('offre/offre-detail.html.twig', ['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'offre' => $offre]);
    }

    #[Route('/postuler', name: 'Postuler')]  // Postuler à une offre
    public function postuler(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        return $this->render('offre/postuler-offre.html.twig', ['ancien_page_title' => 'Offre','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user]);
    }

    #[Route('/avis', name: 'Avis')]  // Avis d'une offre
    public function avis(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        return $this->render('offre/avis-offre.html.twig', ['ancien_page_title' => 'Offre','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user]);
    }
}
