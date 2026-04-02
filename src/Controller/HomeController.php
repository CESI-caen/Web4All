<?php

namespace App\Controller;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request; // Pour gérer les requêtes HTTP
use Symfony\Component\HttpFoundation\Response; // Pour les requêtes et les réponses HTTP
use Symfony\Component\Routing\Annotation\Route; // Pour les routes
use Symfony\Component\HttpFoundation\File\Exception\FileException; // POur gérer les erreurs de déplacement du fichier
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\PdoService;
use App\Model\OffreModel;
use App\Model\UtilisateurModel;
use App\Model\VilleModel;
use App\Model\EntrepriseModel;
use App\Model\AccountModel;
use App\Model\DomaineModel;
use App\Model\CompetenceModel;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'Home')]
    public function index(Request $request): Response
    {
        $pdo = new PdoService();
        $OffreModel = new OffreModel($pdo);
        $offres = $OffreModel->getAllOffres();

        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
       
        return $this->render('home/index.html.twig', ['ancien_page_title' => 'null','page_title' => $currentRoute, 'offres' => $offres, 'userId' => $userId,'user' => $user]);
    }

    #[Route('/recherche', name: 'Recherche')] // route de la page de recherche
    public function recherche(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user'); // Récupère les données de l'utilisateur connecté depuis la session
        $userId = $user['id'] ?? null; // Stocke l'ID de l'utilisateur ou null s'il n'est pas connecté

        // Pour récupérer les données de la recherche depuis l'Url (car verbe GET utilisé)
        $recherche    = $request->query->get('recherche', '');
        $filtre_type  = $request->query->all('filtre_type');  // Tableau [], 'all' est utilisé pour les checkboxes
        $filtre_ville = $request->query->all('filtre_ville'); // Tableau []
        $filtre_date  = $request->query->get('filtre_date');
        $choix_filtres = [
            $recherche,
            $filtre_type,
            $filtre_ville,
            $filtre_date
        ]; // Pour afficher les choix, même quand la page est recharger

        // Récupérer les données des villes et domaines pour les afficher dans les filtres de la page de recherche
        $pdo = new PdoService();

        $VilleModel = new VilleModel($pdo);
        $villes = $VilleModel->getAllCities();

        $DomaineModel = new DomaineModel($pdo);
        $domaines = $DomaineModel->getAllDomains();

        // Afficher la page de recherche avec les données nécessaires
        return $this->render('recherche/recherche.html.twig', [
            'ancien_page_title' => 'Home', 
            'page_title' => $currentRoute, 
            'villes' => $villes,
            'domaines' => $domaines,
            'choix_filtres' => $choix_filtres,
            'filtre_type' => $filtre_type,
            'filtre_ville' => $filtre_ville,
            'filtre_date' => $filtre_date,
            'userId' => $userId
        ]);
    }

    #[Route('/profil', name: 'Profil')]
    public function profil(Request $request): Response
    {
        $UserModel = new UtilisateurModel(new PdoService());
        $VilleModel = new VilleModel(new PdoService());
        $EntrepriseModel = new EntrepriseModel(new PdoService());
        $AccountModel = new AccountModel(new PdoService());

        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $email = $request->request->get('email');
            $ville = $request->request->get('ville');
            $genre = $request->request->get('genre');
            $groupe = $request->request->get('groupe');
            $phone = $UserModel->getUserById($userId)['Telephone'];
            $password = $UserModel->getUserById($userId)['Mdp'];
            if ($UserModel->getUserById($userId)['Id_type_compte'] == 3) {
                $school = null;
            } else {
                $school = $groupe;
            }
            $id_city = $VilleModel->getIdByName($ville)['Id_ville'];

            $id_type_account = $UserModel->getUserById($userId)['Id_type_compte'];

            $UserModel->updateUser( $userId, $nom, $prenom, $genre, $email, $phone, $school, $password, $id_city, $id_type_account);
        }

        $nom = $UserModel->getUserById($userId)['Nom'];
        $prenom = $UserModel->getUserById($userId)['Prenom'];
        $email = $UserModel->getUserById($userId)['Email'];
        $genre = $UserModel->getUserById($userId)['Genre'];
        if ($UserModel->getUserById($userId)['Id_type_compte'] == 3) {
            $groupe = $EntrepriseModel->getEnterpriseByUserId($userId)['Nom'];
            $type_compte = 1;
        } else {
            $groupe = $UserModel->getUserById($userId)['Ecole'];
            $type_compte = 2;
        }
        $ville = $VilleModel->getCityById($UserModel->getUserById($userId)['Id_ville'])['Nom'];
        
        return $this->render('home/profil.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'groupe' => $groupe, 'type_compte' => $type_compte, 'ville' => $ville, 'genre' => $genre]);
    }

    #[Route('/wishlist', name: 'WishList')]
    public function whishList(Request $request): Response
    {   
        $pdo = new PdoService();
        $VouloirModel = new VouloirModel($pdo);
        

        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        $Wishlists = $VouloirModel->getWishLists($userId);

        return $this->render('home/wishlist.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'Wishlists' => $Wishlists]);
    }
    #[Route('/creer_etudiant', name: 'Inscription Etudiant')] // Route pour la page de création d'étudiant
    public function creer(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        
        return $this->render('home/creer_etudiant.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user]);
    }

    #[Route('/entreprise/{id}', name: 'Entreprise')]
    public function afficherEntreprise(int $id, Request $request): Response
    {
        $pdo = new PdoService();
        $EntrepriseModel = new EntrepriseModel($pdo);
        $OffreModel = new OffreModel($pdo);
        $ExercerDansModel = new \App\Model\ExercerDansModel($pdo);
        $VilleModel = new VilleModel($pdo);

        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        // Récupérer les informations de l'entreprise
        $entreprise = $EntrepriseModel->getEnterpriseById($id);
        
        if (!$entreprise) {
            throw $this->createNotFoundException('Entreprise non trouvée');
        }

        // Récupérer les offres de l'entreprise
        $offres = $OffreModel->getOffresByEnterpriseId($id);
        
        // Récupérer les domaines de l'entreprise
        $domaines = $ExercerDansModel->getDomainesByEnterprise($id);
        
        // Récupérer la ville de l'entreprise
        $ville = $VilleModel->getCityById($entreprise['Id_ville']);

        return $this->render('home/entreprise.html.twig', [
            'ancien_page_title' => 'Home',
            'page_title' => $currentRoute,
            'userId' => $userId,
            'user' => $user,
            'entreprise' => $entreprise,
            'offres' => $offres,
            'domaines' => $domaines,
            'ville' => $ville
        ]);
    }

    #[Route('/entreprises', name: 'ListEntreprises')]
    public function listEntreprises(Request $request): Response
    {
        $pdo = new PdoService();
        $EntrepriseModel = new EntrepriseModel($pdo);

        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        $entreprises = $EntrepriseModel->getAllEnterprises();

        return $this->render('home/entreprises.html.twig', [
            'ancien_page_title' => 'Home',
            'page_title' => $currentRoute,
            'userId' => $userId,
            'user' => $user,
            'entreprises' => $entreprises
        ]);
    }

    #[Route('/entreprise/{id}/modifier', name: 'ModifierEntreprise')]
    public function modifierEntreprise(int $id, Request $request): Response
    {
        $pdo = new PdoService();
        $EntrepriseModel = new EntrepriseModel($pdo);
        $VilleModel = new VilleModel($pdo);
        $ExercerDansModel = new \App\Model\ExercerDansModel($pdo);
        $DomaineModel = new DomaineModel($pdo);

        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        // Récupérer l'entreprise
        $entreprise = $EntrepriseModel->getEnterpriseById($id);
        
        if (!$entreprise || $entreprise['Id_utilisateur'] != $userId) {
            throw $this->createNotFoundException('Entreprise non trouvée ou accès non autorisé');
        }

        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $email = $request->request->get('email');
            $telephone = $request->request->get('telephone');
            $descriptif = $request->request->get('descriptif');
            $ville = $request->request->get('ville');
            $domaines = $request->request->all('domaines');

            $villeRow = $VilleModel->getIdByName($ville);
            $idVille = $villeRow ? (int)$villeRow['Id_ville'] : $entreprise['Id_ville'];

            if ($EntrepriseModel->updateEnterprise($id, $nom, $email, $telephone, $descriptif, $idVille)) {
                // Mettre à jour les domaines
                // Supprimer les anciennes relations
                $anciensDomaines = $ExercerDansModel->getDomainesByEnterprise($id);
                foreach ($anciensDomaines as $domaine) {
                    $ExercerDansModel->deleteRelation($id, $domaine['Id_domaine']);
                }

                // Ajouter les nouveaux domaines
                foreach ($domaines as $domaineName) {
                    $domaineRow = $DomaineModel->getDomainByName($domaineName);
                    if (!$domaineRow) {
                        $DomaineModel->addDomain($domaineName);
                        $domaineRow = $DomaineModel->getDomainByName($domaineName);
                    }
                    $ExercerDansModel->addRelation($id, (int)$domaineRow['Id_domaine']);
                }

                return $this->redirectToRoute('Entreprise', ['id' => $id]);
            }
        }

        $villes = $VilleModel->getAllCities();
        $domaines = $ExercerDansModel->getDomainesByEnterprise($id);
        $tousLesDomaines = $DomaineModel->getAllDomains();
        $ville = $VilleModel->getCityById($entreprise['Id_ville']);

        return $this->render('home/modifier-entreprise.html.twig', [
            'ancien_page_title' => 'Entreprise',
            'page_title' => $currentRoute,
            'userId' => $userId,
            'user' => $user,
            'entreprise' => $entreprise,
            'villes' => $villes,
            'domaines' => $domaines,
            'tousLesDomaines' => $tousLesDomaines,
            'ville' => $ville
        ]);
    }


    #[Route('/cv', name: 'Fichiers Personnels')]  // Route pour la page de documents
    public function document(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        
        return $this->render('home/cv.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user]);    // 'ancien_page_title' => 'Home'  <-- non dynamique
    }

    #[Route('/upload', name: 'upload', methods: ['POST'])]
    public function upload(Request $request, SluggerInterface $slugger): Response 
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
           //$email = $user->getUserIdentifier();
            $email = "louisperrin332@gmail.com";  // test
    
            // On récupère ce qu'il y a avant le @
            $userNameBeforeAt = explode('@', $email)[0];
            
            // On sécurise le nom
            $safeFilename = $slugger->slug($userNameBeforeAt);
            
            // On génère le nom final unique
            $newFilename = $safeFilename . '.' . $file->guessExtension();
            //$newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension(); // Pour avoir plusieurs fichiers par utilisateur.

            // 2. On déplace le fichier UNE SEULE FOIS avec le nouveau nom
            try {
                $file->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $newFilename
                );
                // Optionnel : ajouter un message de succès
                $this->addFlash('success', 'Fichier renommé en ' . $newFilename . ' et enregistré !');
            } catch (FileException $e) {
                // Gérer l'erreur si le dossier n'est pas accessible
                echo "Erreur lors du déplacement du fichier : " . $e->getMessage();
            }
        }

        return $this->redirectToRoute('Fichier Personnels');
    }
}    



    
