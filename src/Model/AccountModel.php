<?php
namespace App\Model;

use App\Service\PdoService;

class AccountModel
{
    private \PDO $pdo;

    public function __construct(PdoService $pdoService)
    {
        $this->pdo = $pdoService->getPdo();
    }

    public function getAllAccount(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM Types_compte");
        return $stmt->fetchAll();
    }

    public function getAccountById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT Nom FROM Types_compte WHERE Id_type_compte = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getIdByAccount(string $nom): array|false
    {
        $stmt = $this->pdo->prepare("SELECT Id_type_compte FROM Types_compte WHERE Nom = :nom");
        $stmt->execute(['nom' => $nom]);
        return $stmt->fetch();
    }

    public function addAccount(string $type): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO Types_compte (type) VALUES (:type)");
        return $stmt->execute(['type' => $type]);
    }

    public function updateAccount(int $id, string $type): bool
    {
        $stmt = $this->pdo->prepare("UPDATE Types_compte SET type = :type WHERE Id_type_compte = :id");
        return $stmt->execute(['type' => $type, 'id' => $id]);
    }

    public function deleteAccount(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Types_compte WHERE Id_type_compte = :id");
        return $stmt->execute(['id' => $id]);
    }
}