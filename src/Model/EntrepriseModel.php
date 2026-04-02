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
        $requete = $this->pdo->query("SELECT * FROM Entreprises");
        return $requete->fetchAll();
    }

    public function getEnterpriseById(int $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT * FROM Entreprises WHERE Id_entreprise = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    public function getEnterpriseByEmail(string $email): array|false
    {
        $requete = $this->pdo->prepare("SELECT * FROM Entreprises WHERE Email = :email");
        $requete->execute(['email' => $email]);
        return $requete->fetch();
    }

    public function getIdByEntreprise(string $nom): array|false
    {
        $requete = $this->pdo->prepare("SELECT Id_entreprise FROM Entreprises WHERE Nom = :nom");
        $requete->execute(['nom' => $nom]);
        return $requete->fetch();
    }

    public function getEnterpriseByUserId(int $userId): array|false
    {
        $requete = $this->pdo->prepare("SELECT * FROM Entreprises WHERE Id_utilisateur = :user_id");
        $requete->execute(['user_id' => $userId]);
        return $requete->fetch();
    }

    public function addEnterprise(string $nom, string $email, string $telephone, string $descriptif, int $idVille, int $idUtilisateur): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Entreprises (Nom, Email, Telephone, Descriptif, Id_ville, Id_utilisateur) VALUES (:nom, :email, :telephone, :descriptif, :id_ville, :id_utilisateur)");
        return $requete->execute([
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
        $requete = $this->pdo->prepare("UPDATE Entreprises SET Nom = :nom, Email = :email, Telephone = :telephone, Descriptif = :descriptif, Id_ville = :id_ville WHERE Id_entreprise = :id");
        return $requete->execute([
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
        $requete = $this->pdo->prepare("UPDATE Entreprises SET Id_utilisateur = :id_user WHERE Id_entreprise = :id");
        return $requete->execute([
            'id_user' => $id_User,
            'id' => $id
        ]);
    }

    public function deleteEnterprise(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Entreprises WHERE Id_entreprise = :id");
        return $requete->execute(['id' => $id]);
    }
}