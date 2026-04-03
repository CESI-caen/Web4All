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
use App\Model\AccountModel;
use App\Model\EntrepriseModel;
use App\Model\UtilisateurModel;
use App\Model\PostulerModel;

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
        $EntrepriseModel = new EntrepriseModel($pdo);
        $userModel = new UtilisateurModel($pdo);
        $Note1 = new Noter1Model($pdo);

        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $userSession = $request->getSession()->get('user');
        $userId = $userSession['id'] ?? null;

        $offre = $OffreModel->getOffreById($id);
        $notes = $Note1->getNotesByOffer($offre['Id_offre']);
    

        // Récupérer l'entreprise associée à l'offre
        $entrepriseRow = $OffreModel->getEntrepriseIdByOffreId($id);
        $entreprise = $EntrepriseModel->getEnterpriseById((int)$entrepriseRow['Id_entreprise']);

        return $this->render('offre/offre-detail.html.twig', [
            'ancien_page_title' => 'Home',
            'page_title' => $currentRoute,
            'userId' => $userId,
            'user' => $userSession,
            'offre' => $offre,
            'entreprise' => $entreprise,
            'notes' => $notes,
            'offre_id' => $id
        ]);
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
        return $this->render('offre/offre-detail.html.twig', ['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'offre' => $offre, 'offre_id' => $id, 'notes' => $Note]);
    }

    #[Route('/candidature', name: 'Candidature')]
    public function whishList(Request $request): Response
    {   
        $pdo = new PdoService();
        $PostulerModel = new PostulerModel($pdo);
        

        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        $Candidatures = $PostulerModel->getCandidatures($userId);

        return $this->render('home/candidature.html.twig',['ancien_page_title' => 'Offre','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'candidatures' => $Candidatures]);
    }

    #[Route('/postuler/{id}', name: 'Postuler')]  // Postuler à une offre
    public function postuler(int $id ,Request $request): Response
    {
        $pdo = new PdoService();
       
        $PostulerModel = new PostulerModel($pdo);
        $currentRoute = $request->attributes->get('_route');
        $userSession = $request->getSession()->get('user');
        $userId = $userSession['id'] ?? null;

        if ($PostulerModel->relationExists($userId, $id)){
            return $this->redirectToRoute('Offre',['id' => $id, 'message' => 'Déja dans tes candidatures']);
        }
        $PostulerModel->addRelation($userId, $id);

        return $this->redirectToRoute('Candidature');
        //return $this->render('offre/postuler-offre.html.twig', ['ancien_page_title' => 'WishList','page_title' => $currentRoute, 'userId' => $userId, 'user' => $userSession]); // modifier le chermin retour 
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
