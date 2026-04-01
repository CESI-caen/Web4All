<?php
namespace App\Controller;

use App\Model\UserModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user_list')]
    public function list(UserModel $userModel): Response
    {
        $users = $userModel->getAllUsers();

        return $this->render('user/list.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/user/{id}', name: 'user_show')]
    public function show(int $id, UserModel $userModel): Response
    {
        $user = $userModel->getUserById($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }
}