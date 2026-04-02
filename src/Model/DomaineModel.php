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
        $requete = $this->pdo->query("SELECT * FROM Domaines");
        return $requete->fetchAll();
    }

    public function getDomainById(int $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT * FROM Domaines WHERE Id_domaine = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    public function getDomainByName(string $nom): array|false
    {
        $requete = $this->pdo->prepare("SELECT * FROM Domaines WHERE Nom = :nom");
        $requete->execute(['nom' => $nom]);
        return $requete->fetch();
    }

    public function addDomain(string $nom): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Domaines (Nom) VALUES (:nom)");
        return $requete->execute(['nom' => $nom]);
    }

    public function updateDomain(int $id, string $nom): bool
    {
        $requete = $this->pdo->prepare("UPDATE Domaines SET Nom = :nom WHERE Id_domaine = :id");
        return $requete->execute(['nom' => $nom, 'id' => $id]);
    }

    public function deleteDomain(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Domaines WHERE Id_domaine = :id");
        return $requete->execute(['id' => $id]);
    }
}
