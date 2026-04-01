<?php
namespace App\Model;

use App\Service\PdoService;

class DomaineModel
{
    private \PDO $pdo;

    public function __construct(PdoService $pdoService)
    {
        $this->pdo = $pdoService->getPdo();
    }

    public function getAllDomains(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM Domaines");
        return $stmt->fetchAll();
    }

    public function getDomainById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Domaines WHERE Id_domaine = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getDomainByName(string $nom): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Domaines WHERE Nom = :nom");
        $stmt->execute(['nom' => $nom]);
        return $stmt->fetch();
    }

    public function addDomain(string $nom): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO Domaines (Nom) VALUES (:nom)");
        return $stmt->execute(['nom' => $nom]);
    }

    public function updateDomain(int $id, string $nom): bool
    {
        $stmt = $this->pdo->prepare("UPDATE Domaines SET Nom = :nom WHERE Id_domaine = :id");
        return $stmt->execute(['nom' => $nom, 'id' => $id]);
    }

    public function deleteDomain(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Domaines WHERE Id_domaine = :id");
        return $stmt->execute(['id' => $id]);
    }
}
