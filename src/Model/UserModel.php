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
        $stmt = $this->pdo->prepare("SELECT * FROM Utilisateurs WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function addUser(string $nom, string $email): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO Utilisateurs (nom, email) VALUES (:nom, :email)");
        return $stmt->execute(['nom' => $nom, 'email' => $email]);
    }

    public function updateUser(int $id, string $nom, string $email): bool
    {
        $stmt = $this->pdo->prepare("UPDATE Utilisateurs SET nom = :nom, email = :email WHERE id = :id");
        return $stmt->execute(['nom' => $nom, 'email' => $email, 'id' => $id]);
    }

    public function deleteUser(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Utilisateurs WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}