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
        $stmt = $this->pdo->query("SELECT * FROM Utilisateurs");
        return $stmt->fetchAll();
    }

    public function getUserById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Utilisateurs WHERE Id_utilisateur = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function addUser(string $nom,string $prenom, string $email, string $telephone, string $password): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO Utilisateurs (nom, prenom, email, telephone, mot_de_passe, Id_ville, Id_type) VALUES (:nom, :prenom, :email, :telephone, :password, 1, 1)");
        return $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'telephone' => $telephone, 'password' => $password]);
    }

    public function updateUser(int $id, string $nom, string $prenom, string $email, string $telephone): bool
    {
        $stmt = $this->pdo->prepare("UPDATE Utilisateurs SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone WHERE id = :id");
        return $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'telephone' => $telephone, 'id' => $id]);
    }

    public function deleteUser(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Utilisateurs WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}