<?php
namespace App\Model;

use App\Service\PdoService;

class UserModel
{
    private \PDO $pdo;

    public function __construct(PdoService $pdoService)
    {
        $this->pdo = $pdoService->getPdo();
    }

    public function getAllUsers(): array
    {
        $requete = $this->pdo->query("SELECT * FROM Utilisateurs");
        return $requete->fetchAll();
    }

    public function getUserByEmail(string $email): array|false
    {
        $requete = $this->pdo->prepare("SELECT * FROM Utilisateurs WHERE email = :email");
        $requete->execute(['email' => $email]);
        return $requete->fetch();
    }

    public function getUserById(int $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT * FROM Utilisateurs WHERE Id_utilisateur = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    public function addUser(string $nom,string $prenom, string $genre, string $email, string $telephone, string $password, $ecole, int $idVille, int $idAccount): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Utilisateurs (nom, prenom, genre, email, telephone, mdp, Ecole, Id_ville, Id_type_compte) VALUES (:nom, :prenom, :genre, :email, :telephone, :password, :ecole, :id_ville, :id_account)");
        return $requete->execute(['nom' => $nom, 'prenom' => $prenom, 'genre' => $genre, 'email' => $email, 'telephone' => $telephone, 'password' => $password, 'ecole' => $ecole, 'id_ville' => $idVille, 'id_account' => $idAccount]);
    }

    public function updateUser(int $id, string $nom, string $prenom, string $genre, string $email, string $telephone): bool
    {
        $requete = $this->pdo->prepare("UPDATE Utilisateurs SET nom = :nom, prenom = :prenom, genre = :genre, email = :email, telephone = :telephone WHERE id = :id");
        return $requete->execute(['nom' => $nom, 'prenom' => $prenom, 'genre' => $genre, 'email' => $email, 'telephone' => $telephone, 'id' => $id]);
    }

    public function deleteUser(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Utilisateurs WHERE Id_utilisateur = :id");
        return $requete->execute(['id' => $id]);
    }
}