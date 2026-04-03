<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\PdoService;
use App\Model\OffreModel;
use App\Model\Noter1Model;
use App\Model\VouloirModel;

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
        $Note1 = new Noter1Model($pdo);

        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        $offre = $OffreModel->getOffreById($id);
        $Note = $Note1->getNotesByOffer($offre['Id_offre']);

        return $this->render('offre/offre-detail.html.twig', ['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'offre' => $offre, 'offre_id' => $id, 'notes' => $Note]);
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

    #[Route('/offre/{id}/avis', name: 'AvisOffre')]  // Avis d'une offre
    public function avis(int $id,Request $request): Response
    {
        
        
        $pdo = new PdoService();
        $OffreModel = new OffreModel($pdo);
        $Note1 = new Noter1Model($pdo);
        $offre = $OffreModel->getOffreById($id);

        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        $notes = $Note1->getRelation($userId, $id);

         if ($request->isMethod('POST')){
            $comment = $request->request->get('comment');
            $note = $request->request->get('note');
            if (!$Note1->relationExists($userId, $id)){
                $Note1->addRelation($userId, $id, $note, $comment);
                $notes = $Note1->getRelation($userId, $id);
                $message = "Votre avis a été ajouté.";
            }else{
                $Note1->updateNoteAndComment($userId, $id, $note, $comment);
                $notes = $Note1->getRelation($userId, $id);
                $message = "Votre avis a été mis à jour.";
            }
         }
    return $this->render('offre/avis-offre.html.twig', ['ancien_page_title' => 'Offre','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'offre' => $offre, 'offre_id' => $id, 'notes' => $notes, 'message' => $message ?? null]);
    }
}
