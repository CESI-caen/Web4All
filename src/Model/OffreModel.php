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
        $requete = $this->pdo->query("SELECT * FROM Offres");
        return $requete->fetchAll();
    }

    public function getAllOffresByUser(int $userId): array
    {
        $requete = $this->pdo->prepare("SELECT * FROM Offres WHERE Id_entreprise IN (SELECT Id_entreprise FROM Entreprises WHERE Id_user = :user_id)");
        $requete->execute(['user_id' => $userId]);
        return $requete->fetchAll();
    }

    public function getOffreById(int $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT * FROM Offres WHERE Id_offre = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    public function getIdByOffre(string $nom): array|false
    {
        $requete = $this->pdo->prepare("SELECT Id_offre FROM Offres WHERE Nom = :nom");
        $requete->execute(['nom' => $nom]);
        return $requete->fetch();
    }

    public function addOffre(string $descriptif, string $date_debut, string $date_fin, int $duree, float $renumeration, int $id_entreprise): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Offres (Descriptif, Date_debut, Date_fin, Duree, Renumeration, Id_entreprise) VALUES (:descriptif, :date_debut, :date_fin, :duree, :renumeration, :id_entreprise)");
        return $requete->execute(['descriptif' => $descriptif, 'date_debut' => $date_debut, 'date_fin' => $date_fin, 'duree' => $duree, 'renumeration' => $renumeration, 'id_entreprise' => $id_entreprise ]);
    }

    public function updateOffre(int $id_offre): bool // à completer avec request->request->get() ( string $descriptif, string $date_debut, string $date_fin, int $duree, float $renumeration)
    {
        $requete = $this->pdo->prepare("UPDATE Offres SET Nom = :nom, Description = :description WHERE Id_offre = :id");
        return $requete->execute(['nom' => $nom, 'description' => $description, 'id' => $id]);
    }

    public function deleteOffre(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Offres WHERE Id_offre = :id");
        return $requete->execute(['id' => $id]);
    }
}