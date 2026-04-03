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
            'filtre_ville'   => $request->query->all('filtre_ville[]'),
            'filtre_date'    => $request->query->get('filtre_date', ''),
            'filtre_domaine' => $request->query->all('filtre_domaine[]'),
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
        $filtre_ville   = $request->query->all('filtre_ville');
        $filtre_date    = $request->query->get('filtre_date', '');
        $filtre_domaine = $request->query->all('filtre_domaine');

        // TODO : remplacer par la vraie requête filtrée dans OffreModel
        $offres = [
            [
                'Nom'          => 'Test test',
                'Id_offre'     => 1,
                'Descriptif'   => 'testetestest',
                'Date_debut'   => '2024-01-01',
                'Date_fin'     => '2024-01-01',
                'Id_entreprise'=> 1,
                'Id_domaine'   => 1,
            ]
        ];

        return $this->render('home/index.html.twig', [
            'ancien_page_title' => 'Recherche',
            'page_title'        => 'Home',
            'offres'            => $offres,
            'user'              => $user,
            'userId'            => $userId,
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

        return $this->render('home/entreprise.html.twig', [
            'ancien_page_title' => 'Recherche',
            'page_title'        => 'Entreprise',
            'entreprises'       => $entreprises,
            'user'              => $user,
            'userId'            => $userId,
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

        return $this->render('home/profil.html.twig', [
            'ancien_page_title' => 'Recherche',
            'page_title'        => 'Profil',
            'profils'           => $profils,
            'user'              => $user,
            'userId'            => $userId,
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

    #[Route('/entreprise', name: 'Entreprise')]
    public function entreprise(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        
        return $this->render('home/entreprise.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user]);
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



    
