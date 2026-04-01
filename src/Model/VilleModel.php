<?php
namespace App\Model;

use App\Service\PdoService;

class VilleModel
{
    private \PDO $pdo;

    public function __construct(PdoService $pdoService)
    {
        $this->pdo = $pdoService->getPdo();
    }

    public function getAllCities(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM Villes");
        return $stmt->fetchAll();
    }

    public function getCityById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT Nom FROM Villes WHERE Id_ville = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getIdByName(string $nom): array|false
    {
        $stmt = $this->pdo->prepare("SELECT Id_ville FROM Villes WHERE nom = :nom");
        $stmt->execute(['nom' => $nom]);
        return $stmt->fetch();
    }

    public function addCity(string $nom, string $codePostal): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO Villes (nom, CP) VALUES (:nom, :code_postal)");
        return $stmt->execute(['nom' => $nom, 'code_postal' => $codePostal]);
    }

    public function updateCity(int $id, string $nom): bool
    {
        $stmt = $this->pdo->prepare("UPDATE Villes SET nom = :nom WHERE Id_ville = :id");
        return $stmt->execute(['nom' => $nom, 'id' => $id]);
    }

    public function deleteCity(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Villes WHERE Id_ville = :id");
        return $stmt->execute(['id' => $id]);
    }
}