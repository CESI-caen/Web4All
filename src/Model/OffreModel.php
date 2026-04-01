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

    public function getAllOffresByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Offres WHERE Id_entreprise IN (SELECT Id_entreprise FROM Entreprises WHERE Id_user = :user_id)");
        $stmt->execute(['user_id' => $userId]);
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

    public function addOffre(string $descriptif, string $date_debut, string $date_fin, int $duree, float $renumeration, int $id_entreprise): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO Offres (Descriptif, Date_debut, Date_fin, Duree, Renumeration, Id_entreprise) VALUES (:descriptif, :date_debut, :date_fin, :duree, :renumeration, :id_entreprise)");
        return $stmt->execute(['descriptif' => $descriptif, 'date_debut' => $date_debut, 'date_fin' => $date_fin, 'duree' => $duree, 'renumeration' => $renumeration, 'id_entreprise' => $id_entreprise ]);
    }

    public function updateOffre(int $id_offre): bool // à completer avec request->request->get() ( string $descriptif, string $date_debut, string $date_fin, int $duree, float $renumeration)
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