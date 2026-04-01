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
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $user = $userModel->getUserByEmail($email);
            if ($user && $user['Mdp'] === $password) {
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
        $error = null;

        if ($request->isMethod('POST') && $request->request->get('nom') && $request->request->get('prenom')) {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $telephone = $request->request->get('telephone');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $genre = $request->request->get('genre');
            $ecole = $request->request->get('ecole');
            $account = $request->request->get('account_type');
            $ville = $request->request->get('ville');
            $cp = $request->request->get('cp');

            if ($userModel->getUserByEmail($email)) {
                $error = 'Cet e-mail est déjà utilisé. Veuillez en choisir un autre.';
                $page = 1;
            } elseif ($password != $request->request->get('password_confirm')) {
                $error = 'Les mots de passe ne correspondent pas.';
                $page = 2;
            } 
            else {
                if ($account === 'Recruteur') {
                    $ecole = null;
                }

                $villeRow = $VilleModel->getIdByName($ville);
                if (!$villeRow) {
                    $VilleModel->addCity($ville, $cp);
                    $villeRow = $VilleModel->getIdByName($ville);
                }

                $accountRow = $AccountModel->getIdByAccount($account);

                if ($villeRow && $accountRow) {
                    $idVille = (int) $villeRow['Id_ville'];
                    $idAccount = (int) $accountRow['Id_type_compte'];

                    if ($userModel->addUser($nom, $prenom, $genre, $email, $telephone, $password, $ecole, $idVille, $idAccount)) {
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

        if ($page == 2 && $email && !$error) {
            $existingUser = $userModel->getUserByEmail($email);
            if ($existingUser) {
                $error = 'Cet e-mail est déjà utilisé. Veuillez en choisir un autre.';
                $page = 1;
            }
        }

        return $this->render('authentification/create-account.html.twig', ['page' => $page, 'email' => $email, 'error' => $error]);
    }
}
