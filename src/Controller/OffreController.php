<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\PdoService;
use App\Model\OffreModel;
use App\Model\VouloirModel;
use \App\Model\EntrepriseModel;

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
        $EntrepriseModel = new EntrepriseModel($pdo);

        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        $offre = $OffreModel->getOffreById($id);

        // Récupérer l'entreprise associée à l'offre
        $entrepriseRow = $OffreModel->getEntrepriseIdByOffreId($id);
        $entreprise = $EntrepriseModel->getEnterpriseById((int)$entrepriseRow['Id_entreprise']);

        return $this->render('offre/offre-detail.html.twig', ['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'offre' => $offre, 'entreprise' => $entreprise]);
    }

    #[Route('/offre/{id}/modifier', name: 'ModifierOffre')]
    public function modifierOffre(int $id, Request $request): Response
    {
        $pdo = new PdoService();
        $OffreModel = new OffreModel($pdo);
        $EntrepriseModel = new EntrepriseModel($pdo);

        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        // Récupérer l'offre
        $offre = $OffreModel->getOffreById($id);
        
        if (!$offre) {
            throw $this->createNotFoundException('Offre non trouvée');
        }

        // Récupérer l'entreprise pour vérifier les permissions
        $entrepriseRow = $OffreModel->getEntrepriseIdByOffreId($id);
        $entreprise = $EntrepriseModel->getEnterpriseById((int)$entrepriseRow['Id_entreprise']);
        
        if ($entreprise['Id_utilisateur'] != $userId) {
            throw $this->createNotFoundException('Offre non trouvée ou accès non autorisé');
        }

        if ($request->isMethod('POST')) {
            $descriptif = $request->request->get('descriptif');
            $date_start = $request->request->get('date_start');
            $date_end = $request->request->get('date_end');
            $duration = (int)$request->request->get('duration');
            $salary = (float)$request->request->get('salary');

            if ($OffreModel->updateOffre($id, $descriptif, $date_start, $date_end, $duration, $salary)) {
                return $this->redirectToRoute('Offre', ['id' => $id]);
            }
        }

        return $this->render('offre/modifier-offre.html.twig', [
            'ancien_page_title' => 'Offre',
            'page_title' => $currentRoute,
            'userId' => $userId,
            'user' => $user,
            'offre' => $offre,
            'entreprise' => $entreprise
        ]);
    }

    #[Route('/postuler', name: 'Postuler')]  // Postuler à une offre
    public function postuler(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        return $this->render('offre/postuler-offre.html.twig', ['ancien_page_title' => 'MesOffres','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user]); // modifier le chermin retour 
    }

    #[Route('/wishlist_ajout/{id}', name: 'Wishlist_ajout')]
    public function wishlist_ajout(int $id, Request $request): Response
    {
        $pdo = new PdoService();
        $VouloirModel = new VouloirModel($pdo);
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
    
        $VouloirModel->addRelation($userId, $id);

        // Rediriger vers la page de Home après ajout wishlist
        return $this->redirectToRoute('Home');
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
