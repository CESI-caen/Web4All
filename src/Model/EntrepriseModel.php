<?php
namespace App\Model;

use App\Service\PdoService;

class EntrepriseModel
{
    private \PDO $pdo;

    public function __construct(PdoService $pdoService)
    {
        $this->pdo = $pdoService->getPdo();
    }

    public function getAllEnterprises(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM Entreprises");
        return $stmt->fetchAll();
    }

    public function getEnterpriseById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Entreprises WHERE Id_entreprise = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getIdByEntreprise(string $nom): array|false
    {
        $stmt = $this->pdo->prepare("SELECT Id_entreprise FROM Entreprises WHERE Nom = :nom");
        $stmt->execute(['nom' => $nom]);
        return $stmt->fetch();
    }

    public function getEnterpriseByUserId(int $userId): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Entreprises WHERE Id_utilisateur = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch();
    }

    public function addEnterprise(string $nom, string $email, string $telephone, string $descriptif, int $idVille, int $idUtilisateur): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO Entreprises (Nom, Email, Telephone, Descriptif, Id_ville, Id_utilisateur) VALUES (:nom, :email, :telephone, :descriptif, :id_ville, :id_utilisateur)");
        return $stmt->execute([
            'nom' => $nom,
            'email' => $email,
            'telephone' => $telephone,
            'descriptif' => $descriptif,
            'id_ville' => $idVille,
            'id_utilisateur' => $idUtilisateur
        ]);
    }

    public function updateEnterprise(int $id, string $nom, string $email, string $telephone, string $descriptif, int $idVille): bool
    {
        $stmt = $this->pdo->prepare("UPDATE Entreprises SET Nom = :nom, Email = :email, Telephone = :telephone, Descriptif = :descriptif, Id_ville = :id_ville WHERE Id_entreprise = :id");
        return $stmt->execute([
            'nom' => $nom,
            'email' => $email,
            'telephone' => $telephone,
            'descriptif' => $descriptif,
            'id_ville' => $idVille,
            'id' => $id
        ]);
    }

    public function updateEnterpriseUser(int $id, int $id_User ): bool
    {
        $stmt = $this->pdo->prepare("UPDATE Entreprises SET Id_utilisateur = :id_user WHERE Id_entreprise = :id");
        return $stmt->execute([
            'id_user' => $id_User,
            'id' => $id
        ]);
    }

    public function deleteEnterprise(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Entreprises WHERE Id_entreprise = :id");
        return $stmt->execute(['id' => $id]);
    }
}