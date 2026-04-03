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
use App\Model\VouloirModel;
use App\Model\Noter1Model;
use App\Model\Noter2Model;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'Home')]
    public function index(Request $request): Response
    {
        if (!$request->getSession()->has('user')) {
            return $this->redirectToRoute('Login');
        }
        $pdo = new PdoService();
        $OffreModel = new OffreModel($pdo);
        $offres = $OffreModel->getAllOffres();

        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        $perpage = 2;
        $nbpages = ceil(count($offres) / $perpage);
        $pageParam = $request->query->get('page');
        $page = ($pageParam && $pageParam > 0 && $pageParam <= $nbpages) ? (int)$pageParam : 1;

       
        return $this->render('home/index.html.twig', ['ancien_page_title' => 'null','page_title' => $currentRoute, 'offres' => array_slice($offres, ($page - 1) * $perpage, $perpage), 'userId' => $userId,'user' => $user, 'page' => $page, 'nbpages' => $nbpages, 'baseUrl' => $this->generateUrl('Home')]);
    }

    // -----------------------------------------------------------------------
    // ROUTE 1 : Formulaire de recherche — affiche UNIQUEMENT le formulaire,
    //           puis redirige vers la bonne route de résultats.
    // -----------------------------------------------------------------------

    #[Route('/recherche', name: 'Recherche')]
    public function recherche(Request $request): Response
    {
        $pdo = new PdoService();
        $user     = $request->getSession()->get('user');
        $userId   = $user['id'] ?? null;

        $villes  = (new VilleModel($pdo))->getAllCities();
        $domaines = (new DomaineModel($pdo))->getAllDomains();

        // On lit filtre_type uniquement pour savoir où rediriger.
        // Si absent (visite directe de /recherche), on affiche le formulaire.
        $filtre_type = $request->query->get('filtre_type');

        if (!$filtre_type) {
            return $this->render('recherche/recherche.html.twig', [
                'ancien_page_title' => 'Home',
                'page_title'        => 'Recherche',
                'villes'            => $villes,
                'domaines'          => $domaines,
                'user'              => $user,
                'userId'            => $userId,
            ]);
        }

        // On regroupe tous les filtres pour les passer à la route de résultats. Pour faire ensuite lkes requêtes filtrées dans les modèles.
        $params = [
            'recherche'      => $request->query->get('recherche', ''),
            'filtre_ville'   => $request->query->all('filtre_ville'),
            'filtre_date'    => $request->query->get('filtre_date', ''),
            'filtre_domaine' => $request->query->all('filtre_domaine'),
        ];

        // redirectToRoute génère une vraie réponse HTTP 302.
        // Le navigateur change d'URL — les deux états deviennent distincts.
        $routeMap = [
            'offres'      => 'ResultatsOffres',
            'entreprises' => 'ResultatsEntreprises',
            'comptes'     => 'ResultatsComptes',
        ];

        // Si filtre_type ne correspond à rien de connu, on reste sur le formulaire.
        if (!array_key_exists($filtre_type, $routeMap)) {
            return $this->render('recherche/recherche.html.twig', [
                'ancien_page_title' => 'Home',
                'page_title'        => 'Recherche',
                'villes'            => $villes,
                'domaines'          => $domaines,
                'user'              => $user,
                'userId'            => $userId,
            ]);
        }

        return $this->redirectToRoute($routeMap[$filtre_type], $params);
    }


    // -----------------------------------------------------------------------
    // ROUTE 2 : Résultats — Offres
    // URL : /resultats/offres?recherche=...&filtre_ville[]=...
    // -----------------------------------------------------------------------

    #[Route('/resultat/offres', name: 'ResultatsOffres')] // C'est bien Home qui affiche les offres
    public function resultatsOffres(Request $request): Response
    {
        $pdo    = new PdoService();
        $user   = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        $recherche      = $request->query->get('recherche', '');
        $filtre_domaine = $request->query->all('filtre_domaine');
        if (empty($filtre_domaine)) {
            $filtre_domaine = null; // Pour que le modèle sache qu'il ne doit pas filtrer par domaine
        } elseif (is_array($filtre_domaine)) {
            foreach ($filtre_domaine as $domaine) {
                $domaine = (string) $domaine; // Conversion de chaque domaine en string, pour que le modèle puisse faire la requête SQL correctement
            }
        }
        $filtre_ville   = $request->query->all('filtre_ville');
        if (empty($filtre_ville)) {
            $filtre_ville = null; // Pour que le modèle sache qu'il ne doit pas filtrer par ville
        } elseif (is_array($filtre_ville)) {
            foreach ($filtre_ville as $ville) {
                $ville = (string) $ville; // Conversion de chaque ville en string, pour que le modèle puisse faire la requête SQL correctement
            }
        }
        $filtre_date    = $request->query->get('filtre_date', '');


        switch ($filtre_date) {
            case 'toutes':
                $date_publication = null; // Pas de filtre sur la date
                break;
            case '24h': // Cette semaine
                $date_publication = date('Y-m-d', strtotime('-1 day'));
                break;
            case '7j': // Ce mois-ci
                $date_publication = date('Y-m-d', strtotime('-7 days'));
                break;
            case 'mois': // Ce mois-ci
                $date_publication = date('Y-m-01'); // Premier jour du mois en cours
                break;
        }

        $offres = (new OffreModel($pdo))->filterOffresForSearch($filtre_domaine, $filtre_ville, $date_publication);

        $perpage = 2;
        $nbpages = ceil(count($offres) / $perpage);
        $pageParam = $request->query->get('page');
        $page = ($pageParam && $pageParam > 0 && $pageParam <= $nbpages) ? (int)$pageParam : 1;

        return $this->render('home/index.html.twig', [
            'ancien_page_title' => 'Recherche',
            'page_title'        => 'Home',
            'offres'            => $offres,
            'user'              => $user,
            'userId'            => $userId,
            'page'              => $page,
            'nbpages'           => $nbpages,
            'baseUrl'           => $this->generateUrl('Recherche')
        ]);
    }


    // -----------------------------------------------------------------------
    // ROUTE 3 : Résultats — Entreprises
    // -----------------------------------------------------------------------

    #[Route('/resultats/entreprises', name: 'ResultatsEntreprises')]
    public function resultatsEntreprises(Request $request): Response
    {
        $pdo    = new PdoService();
        $user   = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        $recherche = $request->query->get('recherche', '');
        // TODO : requête filtrée dans EntrepriseModel

        $entreprises = [['Nom' => 'Test test']];

        $perpage = 2;
        $nbpages = ceil(count($entreprises) / $perpage);
        $pageParam = $request->query->get('page');
        $page = ($pageParam && $pageParam > 0 && $pageParam <= $nbpages) ? (int)$pageParam : 1;

        return $this->render('home/entreprise.html.twig', [
            'ancien_page_title' => 'Recherche',
            'page_title'        => 'ListEntreprises',
            'entreprises'       => $entreprises,
            'user'              => $user,
            'userId'            => $userId,
            'page'              => $page,
            'nbpages'           => $nbpages,
            'baseUrl'           => $this->generateUrl('Recherche')
        ]);
    }


    // -----------------------------------------------------------------------
    // ROUTE 4 : Résultats — Comptes
    // -----------------------------------------------------------------------

    #[Route('/resultats/comptes', name: 'ResultatsComptes')]
    public function resultatsComptes(Request $request): Response
    {
        $pdo    = new PdoService();
        $user   = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        $recherche = $request->query->get('recherche', '');
        // TODO : requête filtrée dans UtilisateurModel

        $profils = [['Nom' => 'Test test']];

        $perpage = 2;
        $nbpages = ceil(count($profils) / $perpage);
        $pageParam = $request->query->get('page');
        $page = ($pageParam && $pageParam > 0 && $pageParam <= $nbpages) ? (int)$pageParam : 1;

        return $this->render('home/profil.html.twig', [
            'ancien_page_title' => 'Recherche',
            'page_title'        => 'Profil',
            'profils'           => $profils,
            'user'              => $user,
            'userId'            => $userId,
            'page'              => $page,
            'nbpages'           => $nbpages,
            'baseUrl'           => $this->generateUrl('Recherche')
        ]);
    }

    #[Route('/profil', name: 'Profil')]
    public function profil(Request $request): Response
    {
        if (!$request->getSession()->has('user') || $request->getSession()->get('user')['id'] == 999) {
            return $this->redirectToRoute('Login');
        }

         $UserModel = new UtilisateurModel(new PdoService());
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
        $villes = $VilleModel->getAllCities();
        
        return $this->render('home/profil.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'groupe' => $groupe, 'type_compte' => $type_compte, 'ville' => $ville,'villes' => $villes, 'genre' => $genre]);
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

         if ($request->isMethod('POST')) {
            $pdo = new PdoService();
            $userModel = new UtilisateurModel($pdo);
            $AccountModel = new AccountModel($pdo);
            $VilleModel = new VilleModel($pdo);

            $idAcount =  $AccountModel->getIdByAccount('etudiant');

            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $genre = $request->request->get('genre');
            $phone = $request->request->get('tel');
            $email = $request->request->get('email');
            $userwithEmail = $userModel->getUserByEmail($email);
            
            if ($userwithEmail != false) { // Permet de vérifier si l'email existe déja ( Si non ça va retourner false donc on veut pas rentrer dans le if )
                return $this->render('home/creer_etudiant.html.twig', ['message' => 'Cet email est déjà utilisé.', 'ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user]);
            }
            $password = $request->request->get('password');
            $password_confirm = $request->request->get('password_confirm');
            if ($password !== $password_confirm) {
                return $this->render('home/creer_etudiant.html.twig', ['message' => 'Les mots de passe ne correspondent pas.', 'ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user]);
            }

            $school = $user['ecole'] ?? null;
            $id_ville = $user['id_ville'] ?? null;
            $id_account = $AccountModel->getIdByAccount('etudiant')['Id_type_compte'];

            $userModel->addUser($nom, $prenom, $genre, $email, $phone, $password, $school, $id_ville, $id_account);
         }
        
        return $this->render('home/creer_etudiant.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'message' => 'Compte créé avec succès !']);
    }

    #[Route('/entreprise/{id}', name: 'Entreprise')]
    public function afficherEntreprise(int $id, Request $request): Response
    {
        $pdo = new PdoService();
        $EntrepriseModel = new EntrepriseModel($pdo);
        $OffreModel = new OffreModel($pdo);
        $ExercerDansModel = new \App\Model\ExercerDansModel($pdo);
        $VilleModel = new VilleModel($pdo);
        $Note2 = new Noter2Model($pdo);

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

        // Récupérer les notes pour chaque offre d'entreprise (optionnel)
        $notes = $Note2->getNotesByCompany($id);

        return $this->render('home/entreprise.html.twig', [
            'ancien_page_title' => 'ListEntreprises',
            'page_title' => $currentRoute,
            'userId' => $userId,
            'user' => $user,
            'entreprise' => $entreprise,
            'offres' => $offres,
            'domaines' => $domaines,
            'ville' => $ville,
            'notes' => $notes,
        ]);
    }

    #[Route('/entreprise/{id}/avis', name: 'AvisEntreprise')]  // Avis d'une offre
    public function avis(int $id,Request $request): Response
    {
        
        
        $pdo = new PdoService();
        $EntrepriseModel = new EntrepriseModel($pdo);
        $Note2 = new Noter2Model($pdo);
        $entreprise = $EntrepriseModel->getEnterpriseById($id);

        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        $notes = $Note2->getRelation($userId, $id);

         if ($request->isMethod('POST')){
            $comment = $request->request->get('comment');
            $note = $request->request->get('note');
            if (!$Note2->relationExists($userId, $id)){
                $Note2->addRelation($userId, $id, $note, $comment);
                $notes = $Note2->getRelation($userId, $id);
                $message = "Votre avis a été ajouté.";
            }else{
                $Note2->updateNoteAndComment($userId, $id, $note, $comment);
                $notes = $Note2->getRelation($userId, $id);
                $message = "Votre avis a été mis à jour.";
            }
         }
    return $this->render('offre/avis-entreprise.html.twig', ['ancien_page_title' => 'Entreprise','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'entreprise' => $entreprise, 'entreprise_id' => $id, 'notes' => $notes, 'message' => $message ?? null]);
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
        if (!$request->getSession()->has('user')) {
            return $this->redirectToRoute('Login');
        }
        if ( $request->getSession()->get('user')['id'] == 999 || $request->getSession()->get('user')['nom_type_compte'] != 'recruteur') {
            return $this->redirectToRoute('Home');
        }
        $pdo = new PDOService();
        $userModel = new UtilisateurModel($pdo);
        $EntrepriseModel = new EntrepriseModel($pdo);
        $OffreModel = new OffreModel($pdo);
        $VilleModel = new VilleModel($pdo);
        $Note2 = new Noter2Model($pdo);
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        $Entreprise = $EntrepriseModel->getEnterpriseByUserId($userId);
        $Offres = $OffreModel->getOffreById_entreprise($Entreprise['Id_entreprise']);
        $ville = $VilleModel->getCityById($Entreprise['Id_ville'])['Nom'];
        $Note = $Note2->getNotesByCompany($Entreprise['Id_entreprise']);
        

        
        return $this->render('home/entreprise.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'entreprise' => $Entreprise, 'offres' => $Offres, 'ville' => $ville, 'notes' => $Note]);
    }

    #[Route('/cv', name: 'Fichiers Personnels')]  // Route pour la page de documents
    public function document(Request $request): Response
    {   
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        
        return $this->render('home/cv.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'message' => $request->query->get('message')]);    // 'ancien_page_title' => 'Home'  <-- non dynamique
    }

    #[Route('/upload', name: 'upload', methods: ['POST'])]
    public function upload(Request $request, SluggerInterface $slugger): Response 
    {
        $pdo = new PDOService();
        $userModel = new UtilisateurModel($pdo);
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        $user_Cv = $userModel->getUserById($userId)['Cv'];

        $file = $request->files->get('file');

        $message = "Vous avez déja un CV enregistré";

        if ($file && $user_Cv == null) {

            // Exemple de conditions à ajouter pour limiter les types de fichiers et la taille (non fonctionnel pour le moment, à revoir) :

            $allowedTypes = ['application/pdf','image/png','image/jpeg'];

            if (!in_array($file->getMimeType(), $allowedTypes)) {
                $message = "Type de ficheir non autorisé ! ";
                return $this->redirectToRoute('Fichiers Personnels', ['message' => $message]);
            }

            if ($file->getSize() > 200000) { // 200 KB
                $message = "Le fichier est trop lourd ! ";
                return $this->redirectToRoute('Fichiers Personnels', ['message' => $message]);
            }
            
            
            $email =$user['email'] ?? null;
    
            // On récupère ce qu'il y a avant le @
            $userNameBeforeAt = explode('@', $email)[0];
            
            // On sécurise le nom
            $safeFilename = $slugger->slug($userNameBeforeAt);
            
            $uniqueId = uniqid();

            $userModel->updateCV($userId, $uniqueId);

            // On génère le nom final unique
            $newFilename = $safeFilename.'-'. $uniqueId . '.' . $file->guessExtension();
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
           $message =  'Fichier téléchargé avec succès !';
        }

        return $this->redirectToRoute('Fichiers Personnels', ['message' => $message]);
    }
}    



    
