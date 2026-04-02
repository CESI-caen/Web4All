<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PdoService;
use App\Model\UserModel;
use App\Model\AccountModel;
use App\Model\VilleModel;
use App\Model\EntrepriseModel;
use App\Model\DomaineModel;
use App\Model\ExercerDansModel;



class AuthentificationController extends AbstractController
{
    #[Route('/login', name: 'Login')]
    public function sign_in(Request $request): Response
    {
        $error = null;
        $success = $request->query->get('success');
        $email = $request->query->get('email', '');

        if ($request->isMethod('POST')) {
            $pdo = new PdoService();
            $userModel = new UserModel($pdo);
            $AccountModel = new AccountModel($pdo);

            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $user = $userModel->getUserByEmail($email);

            if ($user && $user['Mdp'] === $password) { //password_verify($password, $user['Mdp'])

                $request->getSession()->set('user', [
                    'id' => $user['Id_utilisateur'],
                    'nom' => $user['Nom'],
                    'prenom' => $user['Prenom'],
                    'email' => $user['Email'],
                    'telephone' => $user['Telephone'],
                    'genre' => $user['Genre'],
                    'ecole' => $user['Ecole'],
                    'id_type_compte' => $user['Id_type_compte'],
                    'nom_type_compte' => $AccountModel->getAccountById($user['Id_type_compte'])['Nom']
                ]);
                
                return $this->redirectToRoute('Home');
            } else {
                $error = 'Email ou mot de passe incorrect.';
                $success = null; // clear success on error
            }
        }

        return $this->render('authentification/login.html.twig', ['error' => $error, 'success' => $success, 'email' => $email]);
    }

    #[Route('/create-account', name: 'create-account')]
    public function sign_up(Request $request): Response
    {
        $pdo = new PdoService();
        $userModel = new UserModel($pdo);
        $AccountModel = new AccountModel($pdo);
        $VilleModel = new VilleModel($pdo);
        $EntrepriseModel = new EntrepriseModel($pdo);
        $DomaineModel = new DomaineModel($pdo);
        $ExercerDansModel = new ExercerDansModel($pdo);
        $error = null;
        $success = null;
        // Session 
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        if ($request->isMethod('POST') && $request->request->get('nom') && $request->request->get('prenom')) {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $telephone = $request->request->get('telephone');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $genre = $request->request->get('genre');
            $account = $request->request->get('account_type');
            $ville = $request->request->get('ville');

            if ($userModel->getUserByEmail($email)) {
                $error = 'Cet e-mail est déjà utilisé. Veuillez en choisir un autre.';
                $page = 1;
            } elseif ($password != $request->request->get('password_confirm')) {
                $error = 'Les mots de passe ne correspondent pas.';
                $page = 2;
            } 
            else {
                if ($account === 'recruteur') {
                    $ecole = null;
                } else {
                    $ecole = $request->request->get('ecole');
                }

                $villeRow = $VilleModel->getIdByName($ville);

                $accountRow = $AccountModel->getIdByAccount($account);

                if ($villeRow && $accountRow) {
                    $idVille = (int) $villeRow['Id_ville'];
                    $idAccount = (int) $accountRow['Id_type_compte'];

                    if ($userModel->addUser($nom, $prenom, $genre, $email, $telephone, $password, $ecole, $idVille, $idAccount)) {
                        
                        if ($account === 'recruteur') {
                            $EntrepriseNom = $request->request->get('Entreprise');
                            $Villeentreprise = $request->request->get('ville-entreprise');
                            $descriptif = $request->request->get('description-entreprise');
                            $telEntreprise = $request->request->get('telephone-entreprise');
                            $emailEntreprise = $request->request->get('Email-entreprise');
                            $domaine = $request->request->all('domaine');

                            
                            if ($EntrepriseModel->getEnterpriseByEmail($emailEntreprise)) {
                                $error = "Cet e-mail d'entreprise est déjà utilisé. Veuillez en choisir un autre.";
                                $userModel->deleteUser($userModel->getUserByEmail($email)['Id_utilisateur']);
                                $page = 1;
                                return $this->render('authentification/create-account.html.twig', ['page' => $page, 'email' => $email, 'error' => $error, 'userId' => $userId, 'user' => $user]);
                            }

                            $villeRow = $VilleModel->getIdByName($Villeentreprise);

                            $IdUser = $userModel->getUserByEmail($email)['Id_utilisateur'];

                            $EntrepriseModel->addEnterprise($EntrepriseNom, $emailEntreprise, $telEntreprise, $descriptif, (int) $villeRow['Id_ville'], $IdUser);

                            foreach ($domaine as $domaineName) {
                                $domaineRow = $DomaineModel->getDomainByName($domaineName);
                                if (!$domaineRow) {
                                    $DomaineModel->addDomain($domaineName);
                                    $domaineRow = $DomaineModel->getDomainByName($domaineName);
                                }
                                $ExercerDansModel->addRelation($EntrepriseModel->getEnterpriseByUserId($IdUser)['Id_entreprise'], (int) $domaineRow['Id_domaine']);
                            }
                        }
                        $success = "Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.";
                        return $this->redirectToRoute('Login', ['email' => $email, 'success' => $success]);
                    }
                } else {
                    $error = 'Impossible de récupérer le type de compte ou la ville.';
                }
            }
        }

        $page = $request->request->get('page', 1);
        $email = $request->request->get('email', "");
        $villes = $VilleModel->getAllCities();

        if ($page == 2 && $email && !$error) {
            $existingUser = $userModel->getUserByEmail($email);
            if ($existingUser) {
                $error = 'Cet e-mail est déjà utilisé. Veuillez en choisir un autre.';
                $page = 1;
            }
        }
        return $this->render('authentification/create-account.html.twig', ['page' => $page, 'email' => $email,'villes' => $villes ,'error' => $error, 'userId' => $userId, 'user' => $user]);
    }

    #[Route('/guest-login', name: 'GuestLogin')]
    public function guestLogin(Request $request): Response
    {
        // Créer une session d'invité
        $request->getSession()->set('user', [
            'id' => 999,
            'nom' => '',
            'prenom' => 'Connecté en tant que invité',
            'email' => 'invite@gmail.com',
            'telephone' => '0201030405',
            'genre' => 'autre',
            'ecole' => 'Aucune',
            'id_type_compte' => '1',
            'nom_type_compte' => ''
        ]);

        // Rediriger vers la page d'accueil
        return $this->redirectToRoute('Home');
    }

    #[Route('/', name: 'Connexion')]
    public function Connexion(Request $request): Response
    {
        if ($request->getSession()->has('user') && $request->getSession()->get('user')['id'] != 999) {
            return $this->redirectToRoute('Home');
        }else{
            return $this->redirectToRoute('Login');
        }
    }

    #[Route('/deconnexion', name: 'Deconnexion')]
    public function Deconnexion(Request $request): Response
    {
        // Supprimer la session
        $session = $request->getSession();
        $session->invalidate();

        // Rediriger vers la page de login
        return $this->redirectToRoute('Login');
    }
}
