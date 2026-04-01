<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PdoService;
use App\Model\UserModel;



class AuthentificationController extends AbstractController
{
    #[Route('/login', name: 'Login')]
    public function sign_in(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $pdo = new PdoService();
            $userModel = new UserModel($pdo);
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $users = $userModel->getAllUsers();
            foreach ($users as $user) {
                if ($user['Email'] === $email && $user['mot_de_passe'] === $password) {
                    return $this->redirectToRoute('Home');
                }
            }
        }
        return $this->render('authentification/login.html.twig');
    }

    #[Route('/create-account', name: 'create-account')]
    public function sign_up(Request $request): Response
    {
        $pdo = new PdoService();
        $userModel = new UserModel($pdo);
        $error = null;

        if ($request->isMethod('POST') && $request->request->get('nom') && $request->request->get('prenom')) {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $telephone = $request->request->get('telephone');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            if ($userModel->addUser($nom, $prenom, $email, $telephone, $password)) {
                return $this->redirectToRoute('Login', ['email' => $email]);
            }
        }

        $page = $request->request->get('page', 1);
        $email = $request->request->get('email', "");

        if ($page == 2 && $email) {
            foreach ($userModel->getAllUsers() as $user) {
                if ($user['Email'] === $email) {
                    $error = 'Cet e-mail est déjà utilisé. Veuillez en choisir un autre.';
                    $page = 1;
                    break;
                }
            }
        }

        return $this->render('authentification/create-account.html.twig', ['page' => $page, 'email' => $email, 'error' => $error]);
    }
}
