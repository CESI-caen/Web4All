<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\PdoService;
use App\Model\UtilisateurModel;

class ParametreController extends AbstractController
{
    #[Route('/parametres', name: 'Paramètres')]   // Route pour la page de paramètres
    public function settings(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        return $this->render('parametre/index.html.twig',['ancien_page_title' => 'Home','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user]);  // 'ancien_page_title' => 'Home'  <-- non dynamique
    }

    #[Route('/parametres/langage', name: 'Langage')]   // Route pour la page de paramètres
    public function Language(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        return $this->render('parametre/langage.html.twig',['ancien_page_title' => 'Paramètres','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user]);  // 'ancien_page_title' => 'Paramètres'  <-- non dynamique
    }

    #[Route('/parametres/compte', name: 'Compte')]   // Route pour la page de paramètres
    public function Compte(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;
        return $this->render('parametre/compte.html.twig',['ancien_page_title' => 'Paramètres','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user]);  // 'ancien_page_title' => 'Paramètres'  <-- non dynamique
    }

    #[Route('/parametres/modifier-mdp', name: 'Modifier-mdp')]   // Route pour la page de paramètres
    public function Modifiermdp(Request $request): Response
    {   
        $pdo = new PdoService();
        $userModel = new UtilisateurModel($pdo);

        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        $user_bdd = $userModel->getUserByEmail($user['email']);

        $mdp_actuel = $request->request->get('current_password');
        $nouveau_mdp_1 = $request->request->get('password');
        $nouveau_mdp_2 = $request->request->get('password_confirm');

        $message = null;
         

        if ($request->isMethod('POST')) {
            if ($user_bdd && $user_bdd['Mdp'] === $mdp_actuel) { //password_verify($mdp_actuel, $user_bdd['Mdp'])
                
                if ($nouveau_mdp_1 === $nouveau_mdp_2) { 
                    $userModel->updateUserPassword($userId, $nouveau_mdp_1);
                    return $this->redirectToRoute('Deconnexion');
                }else {
                $message = "Les mots de passe ne correspondent pas  !";
                }
            }else{
                $message = "Mot de passe actuel incorrect !";
            }
        }
    return $this->render('parametre/modifier-mdp.html.twig',['ancien_page_title' => 'Paramètres','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'message' => $message]);
    }

    #[Route('/parametres/supprimer-compte', name: 'Supprimer-compte')]   // Route pour la page de paramètres
    public function SupprimerCompte(Request $request): Response
    {   
        $pdo = new PdoService();
        $userModel = new UtilisateurModel($pdo);

        $currentRoute = $request->attributes->get('_route'); // Récupère le nom de la route actuelle
        $user = $request->getSession()->get('user');
        $userId = $user['id'] ?? null;

        $user_bdd = $userModel->getUserByEmail($user['email']);

        $mdp_actuel = $request->request->get('current_password');

        $message = null;
         if ($request->isMethod('POST')) {
            if ($user_bdd && $user_bdd['Mdp'] === $mdp_actuel) { //password_verify($mdp_actuel, $user_bdd['Mdp'])
                $userModel->deleteUser($userId);
                return $this->redirectToRoute('Deconnexion');
            }else{
                $message = "Mot de passe actuel incorrect !";
            } 
         }
    return $this->render('parametre/supprimer-compte.html.twig',['ancien_page_title' => 'Paramètres','page_title' => $currentRoute, 'userId' => $userId, 'user' => $user, 'message' => $message]);
       
    }
}