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

    #[Route('/recherche', name: 'Recherche')] // route de la page de recherche
    public function recherche(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route');
        $user = $request->getSession()->get('user'); // Récupère les données de l'utilisateur connecté depuis la session
        $userId = $user['id'] ?? null; // Stocke l'ID de l'utilisateur ou null s'il n'est pas connecté

        // ###################### LOGIQUE DE LA PAGE DE RECHERCHE ######################

        // Récupérer les données des villes et domaines pour les afficher dans les filtres de la page de recherche
        $pdo = new PdoService();

        $VilleModel = new VilleModel($pdo);
        $villes = $VilleModel->getAllCities();

        $DomaineModel = new DomaineModel($pdo);
        $domaines = $DomaineModel->getAllDomains();

        // ###########################################################################################


        // ###################### LOGIQUE DE REDIRECTION (RESULTATS) ######################

        // Pour récupérer les données de la recherche depuis l'Url (car verbe GET utilisé)
        $recherche    = $request->query->get('recherche', '');
        $filtre_type  = $request->query->get('filtre_type'); 
        $filtre_ville = $request->query->all('filtre_ville[]'); // Tableau [], pour les checkboxes
        $filtre_date  = $request->query->get('filtre_date', '');
        $filtre_domaine = $request->query->all('filtre_domaine[]', '');

        //$filtre_type = is_array($filtre_type) ? $filtre_type : [$filtre_type]; // Assure que $filtre_type est toujours un tableau, même s'il n'y a qu'un seul type de recherche sélectionné

        // $filtre_type_display = implode(', ', array_map('strval', $filtre_type)); // Convertit le tableau en une chaîne de caractères pour l'affichage, en séparant les éléments par des virgules
        // $filtre_ville_display = implode(', ', array_map('strval', $filtre_ville));


        // Si type de recherche est 'offres' -> render('offre/offre-detail.html.twig', ['offres' => $offres]); faire $offres avec OffreModel
        // Si type de recherche est 'entreprises' -> render('home/entreprise.html.twig', ['entreprises' => $entreprises]); faire $entreprises avec EntrepriseModel
        // Si type de recherche est 'comptes' -> render('home/profil.html.twig', ['profils' => $profils]); faire $profils avec UtilisateurModel

        // TODO : faire en sorte de pouvoir faire une nouvelle recherche apres avoir consulter les resultats
        //        actuellement, le resultat de la recherche s'affiche à l'infini, et on ne peut plus aller sur la page de recherche.

        switch ($filtre_type) {
            case 'entreprises':
            $EntrepriseModel = new EntrepriseModel($pdo);
            $entreprises = [['Nom' => 'Test test']];
            // TODO : requete spécial dans Model/recherche.php

            return $this->render('home/entreprise.html.twig', [
                'ancien_page_title' => 'Recherche',
                'page_title' => 'Entreprise',
                'entreprises' => $entreprises,
                'user' => $user,
                'userId' => $userId
            ]);

            case 'comptes':
            $UtilisateurModel = new UtilisateurModel($pdo);
            $profils = [['Nom' => 'Test test']];
            // TODO : requete spécial dans Model/recherche.php

            return $this->render('home/profil.html.twig', [
                'ancien_page_title' => 'Recherche',
                'page_title' => 'Profil',
                'profils' => $profils,
                'user' => $user,
                'userId' => $userId
            ]);

            case 'offres':
            $OffreModel = new OffreModel($pdo);
            $offres = [['Nom' => 'Test test', 'Id_offre' => 1, 'Descriptif' => 'testetestest', 'Date_debut' => '2024-01-01', 'Date_fin' => '2024-01-01', 'Id_entreprise' => 1, 'Id_domaine' => 1]];
            // TODO : requete spécial dans Model/recherche.php

            return $this->render('home/index.html.twig', [
                'ancien_page_title' => 'Recherche',
                'page_title' => 'Home',
                'offres' => $offres,
                'user' => $user,
                'userId' => $userId
            ]);

            default:
                return $this->render('recherche/recherche.html.twig', [
                    'ancien_page_title' => 'Home', 
                    'page_title' => $currentRoute,

                    'villes' => $villes,
                    'domaines' => $domaines,

                    'user' => $user,
                    'userId' => $userId
                ]);
        }
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



    
