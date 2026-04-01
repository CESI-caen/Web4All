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
        $requete = $this->pdo->query("SELECT * FROM Types_compte");
        return $requete->fetchAll();
    }

    public function getAccountById(int $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT Nom FROM Types_compte WHERE Id_type_compte = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    public function getIdByAccount(string $nom): array|false
    {
        $requete = $this->pdo->prepare("SELECT Id_type_compte FROM Types_compte WHERE Nom = :nom");
        $requete->execute(['nom' => $nom]);
        return $requete->fetch();
    }

    public function addAccount(string $type): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Types_compte (type) VALUES (:type)");
        return $requete->execute(['type' => $type]);
    }

    public function updateAccount(int $id, string $type): bool
    {
        $requete = $this->pdo->prepare("UPDATE Types_compte SET type = :type WHERE Id_type_compte = :id");
        return $requete->execute(['type' => $type, 'id' => $id]);
    }

    public function deleteAccount(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Types_compte WHERE Id_type_compte = :id");
        return $requete->execute(['id' => $id]);
    }
}