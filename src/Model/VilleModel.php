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

    /**
     * Retrieves all cities from the database.
     *
     * @return array An associative array of all city records from the Villes table,
     *               where each row is represented as an array with column names as keys.
     *               Returns an empty array if no cities are found.
     */
    public function getAllCities(): array
    {
        $requete = $this->pdo->query("SELECT * FROM Villes");
        return $requete->fetchAll();
    }

    public function getCityById(int $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT Nom FROM Villes WHERE Id_ville = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    public function getIdByName(string $nom): array|false
    {
        $requete = $this->pdo->prepare("SELECT Id_ville FROM Villes WHERE nom = :nom");
        $requete->execute(['nom' => $nom]);
        return $requete->fetch();
    }

    public function addCity(string $nom, string $codePostal): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Villes (nom, CP) VALUES (:nom, :code_postal)");
        return $requete->execute(['nom' => $nom, 'code_postal' => $codePostal]);
    }

    public function updateCity(int $id, string $nom): bool
    {
        $requete = $this->pdo->prepare("UPDATE Villes SET nom = :nom WHERE Id_ville = :id");
        return $requete->execute(['nom' => $nom, 'id' => $id]);
    }

    public function deleteCity(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Villes WHERE Id_ville = :id");
        return $requete->execute(['id' => $id]);
    }
}