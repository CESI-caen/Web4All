<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\PdoService;
use App\Model\OffreModel;
use App\Model\EntrepriseModel;
use App\Model\CandidatureModel;

class OffreController extends AbstractController
{

    #[Route('/mes-offres', name: 'MesOffres')]
    public function mesOffres(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        return $this->render('offre/mes-offres.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId]);
    }

    // ⚠️ Routes plus spécifiques EN PREMIER
    #[Route('/offre/{id}/postuler', name: 'PostulerOffre')]
    public function postuler(Request $request, int $id): Response
    {
        $pdoService = new PdoService();
        $offreModel = new OffreModel($pdoService);
        $entrepriseModel = new EntrepriseModel($pdoService);
        
        // Récupérer l'offre par ID
        $offre = $offreModel->getOffreById($id);
        $entreprise = null;
        
        if ($offre) {
            // Récupérer les détails de l'entreprise
            $entreprise = $entrepriseModel->getEnterpriseById($offre['Id_entreprise']);
        }
        
        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        return $this->render('offre/postuler-offre.html.twig', [
            'ancien_page_title' => 'Offre',
            'page_title' => $currentRoute,
            'userId' => $userId,
            'offre' => $offre,
            'entreprise' => $entreprise
        ]);
    }

    #[Route('/offre/{id}', name: 'AfficherOffre')]
    public function afficherOffreById(Request $request, int $id): Response
    {
        $pdoService = new PdoService();
        $offreModel = new OffreModel($pdoService);
        $entrepriseModel = new EntrepriseModel($pdoService);
        
        // Récupérer l'offre par ID
        $offre = $offreModel->getOffreById($id);
        
        if (!$offre) {
            throw $this->createNotFoundException('Offre non trouvée');
        }
        
        // Récupérer les détails de l'entreprise
        $entreprise = $entrepriseModel->getEnterpriseById($offre['Id_entreprise']);
        
        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        
        return $this->render('offre/offre-detail.html.twig', [
            'ancien_page_title' => 'Home',
            'page_title' => $currentRoute,
            'userId' => $userId,
            'offre' => $offre,
            'entreprise' => $entreprise
        ]);
    }

    #[Route('/offre', name: 'Offre')]
    public function afficherOffre(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        return $this->render('offre/offre-detail.html.twig', ['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId]);
    }

    #[Route('/avis', name: 'Avis')]  // Avis d'une offre
    public function avis(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        return $this->render('offre/avis-offre.html.twig', ['ancien_page_title' => 'Offre','page_title' => $currentRoute, 'userId' => $userId]);
    }

    #[Route('/offre/{id}/postuler/submit', name: 'SubmitCandidature', methods: ['POST'])]
    public function submitCandidature(Request $request, int $id): Response
    {
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        // Vérifier que l'utilisateur est connecté
        if (!$userId) {
            return $this->json(['success' => false, 'message' => 'Vous devez être connecté pour postuler'], 401);
        }

        // Récupérer et valider les données du formulaire
        $data = json_decode($request->getContent(), true);
        $motivation = trim($data['lettre'] ?? '');

        // Validation
        if (empty($motivation) || strlen($motivation) < 50) {
            return $this->json([
                'success' => false,
                'message' => 'La lettre de motivation doit contenir au moins 50 caractères'
            ], 400);
        }

        if (strlen($motivation) > 5000) {
            return $this->json([
                'success' => false,
                'message' => 'La lettre de motivation ne doit pas dépasser 5000 caractères'
            ], 400);
        }

        // Vérifier que l'offre existe
        $pdoService = new PdoService();
        $offreModel = new OffreModel($pdoService);
        $offre = $offreModel->getOffreById($id);

        if (!$offre) {
            return $this->json(['success' => false, 'message' => 'Offre non trouvée'], 404);
        }

        // Vérifier que l'utilisateur n'a pas déjà postulé
        $candidatureModel = new CandidatureModel($pdoService);
        if ($candidatureModel->hasAlreadyApplied($userId, $id)) {
            return $this->json([
                'success' => false,
                'message' => 'Vous avez déjà postulé à cette offre'
            ], 409);
        }

        // Insérer la candidature
        if ($candidatureModel->addCandidature($userId, $id, $motivation)) {
            return $this->json([
                'success' => true,
                'message' => 'Votre candidature a été envoyée avec succès!'
            ]);
        } else {
            return $this->json([
                'success' => false,
                'message' => 'Une erreur s\'est produite lors de l\'envoi de votre candidature'
            ], 500);
        }
    }
}
