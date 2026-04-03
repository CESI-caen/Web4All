<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\PdoService;
use App\Model\OffreModel;
use App\Model\VouloirModel;
use App\Model\AccountModel;
use App\Model\EntrepriseModel;

class OffreController extends AbstractController
{


    #[Route('/mes-offres', name: 'MesOffres')]
    public function mesOffres(Request $request): Response
    {
        $AccountModel = new AccountModel(new PdoService());
        $EntrepriseModel = new EntrepriseModel(new PdoService());
        $OffreModel = new OffreModel(new PdoService());

        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        if (isset($_POST['type-form']) && $_POST['type-form'] === 'edition') {
            $offreNom = $_POST['offre-name'];
            $offreDescription = $_POST['offre-description'];
            $offreStart = $_POST['offre-start'];
            $offreEnd = $_POST['offre-end'];
            $offreSalary = $_POST['offre-salary'];
            $EntrepriseId = $EntrepriseModel->getEnterpriseByUserId($userId)['Id_entreprise'];
            $duration = (new \DateTime($offreEnd))->diff(new \DateTime($offreStart))->days; // Calcul de la durée en jours
            if ($_POST['offre-id']) {
                $OffreModel->updateOffre($_POST['offre-id'], $offreNom, $offreDescription, $offreStart, $offreEnd, $duration, $offreSalary);
            } else {
                $OffreModel->addOffre($offreNom, $offreDescription, $offreStart, $offreEnd, $duration, $offreSalary, $EntrepriseId);
            }
            return $this->redirectToRoute('MesOffres');
        }

        $Offres = $OffreModel->getAllOffresByUserId($userId);

        return $this->render('offre/mes-offres.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'Offres' => $Offres]);
    }

    #[Route('/offre/delete/{id}', name: 'offre_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        $OffreModel = new OffreModel(new PdoService());
        $OffreModel->deleteOffre($id);
        return $this->redirectToRoute('MesOffres');
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
