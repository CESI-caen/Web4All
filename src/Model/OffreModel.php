<?php
namespace App\Model;

use App\Service\PdoService;

class OffreModel
{
    private \PDO $pdo;

    public function __construct(PdoService $pdoService)
    {
        $this->pdo = $pdoService->getPdo();
    }

    public function getAllOffres(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM Offres");
        return $stmt->fetchAll();
    }

    public function getOffreById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Offres WHERE Id_offre = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getIdByOffre(string $nom): array|false
    {
        $stmt = $this->pdo->prepare("SELECT Id_offre FROM Offres WHERE Nom = :nom");
        $stmt->execute(['nom' => $nom]);
        return $stmt->fetch();
    }

    public function addOffre(string $nom, string $description): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO Offres (Nom, Description) VALUES (:nom, :description)");
        return $stmt->execute(['nom' => $nom, 'description' => $description]);
    }

    public function updateOffre(int $id, string $nom, string $description): bool
    {
        $stmt = $this->pdo->prepare("UPDATE Offres SET Nom = :nom, Description = :description WHERE Id_offre = :id");
        return $stmt->execute(['nom' => $nom, 'description' => $description, 'id' => $id]);
    }

    public function deleteOffre(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Offres WHERE Id_offre = :id");
        return $stmt->execute(['id' => $id]);
    }
}