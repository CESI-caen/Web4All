<?php
namespace App\Model;

use App\Service\PdoService;


// Modèle de la Postuler = noter une offre, qui représente la relation entre un utilisateur et une offre : "un étudiant peut postuler à une offre"
{
    private \PDO $pdo;

    public function __construct(PdoService $pdoService)
    {
        $this->pdo = $pdoService->getPdo();
    }

    /**
     * Ajoute une relation dans la table Postuler.
     *
     * @param int $id_user, the ID of the user who is applying to the offer
     * @param int $id_offre, the ID of the offer to which the user is applying
     * @return bool True if the insertion was successful, false otherwise
     */
    public function addRelation(int $id_user, int $id_offre): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Postuler (Id_utilisateur, Id_offre) VALUES (:id_user, :id_offre)");
        return $requete->execute(['id_user' => $id_user, 'id_offre' => $id_offre]);
    }

    /**
     * Vérifie si une relation existe dans la table Postuler.
     *
     * @param int $id_user, the ID of the user for which the relation should be checked
     * @param int $id_offre, the ID of the offer for which the relation should be checked
     * @return bool True if the relation exists, false otherwise
     */
    public function relationExists(int $id_user, int $id_offre): bool
    {
        $requete = $this->pdo->prepare("SELECT 1 FROM Postuler WHERE Id_utilisateur = :id_user AND Id_offre = :id_offre");
        $requete->execute(['id_user' => $id_user, 'id_offre' => $id_offre]);
        return $requete->fetch() !== false;
    }

    /**
     * Supprime une relation de la table Postuler.
     *
     * @param int $id_user, the ID of the user for which the relation should be deleted
     * @param int $id_offre, the ID of the offer for which the relation should be deleted
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteRelation(int $id_user, int $id_offre): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Postuler WHERE Id_utilisateur = :id_user AND Id_offre = :id_offre");
        return $requete->execute(['id_user' => $id_user, 'id_offre' => $id_offre]);
    }

    /**
     * Supprime toutes les relations d'un utilisateur de la table Postuler.
     *
     * @param int $id_user, the ID of the user whose relations should be deleted
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteAllRelationsByUser(int $id_user): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Postuler WHERE Id_utilisateur = :id_user");
        return $requete->execute(['id_user' => $id_user]);
    }

    /**
     * Supprime toutes les relations d'une offre de la table Postuler.
     *
     * @param int $id_offre, the unique identifier of the offer for which all relations should be deleted
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteAllRelationsByOffer(int $id_offre): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Postuler WHERE Id_offre = :id_offre");
        return $requete->execute(['id_offre' => $id_offre]);
    }

    /**
     * Récupère toutes les offres auxquelles un utilisateur a postulé.
     *
     * @param int $id_user, the ID of the user for which to retrieve applied offers
     * @return array An array of offer IDs
     */
    public function getOffersByUser(int $id_user): array
    {
        $requete = $this->pdo->prepare("SELECT Id_offre FROM Postuler WHERE Id_utilisateur = :id_user");
        $requete->execute(['id_user' => $id_user]);
        return $requete->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Récupère tous les utilisateurs ayant postulé à une offre.
     *
     * @param int $id_offre, the ID of the offer for which to retrieve applied users
     * @return array An array of user IDs
     */
    public function getUsersByOffer(int $id_offre): array
    {
        $requete = $this->pdo->prepare("SELECT Id_utilisateur FROM Postuler WHERE Id_offre = :id_offre");
        $requete->execute(['id_offre' => $id_offre]);
        return $requete->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Récupère toutes les relations de la table Postuler.
     *
     * @return array An array of all relations, each relation is an associative array with keys 'Id_utilisateur' and 'Id_offre'
     */
    public function getAllRelations(): array
    {
        $requete = $this->pdo->query("SELECT Id_utilisateur, Id_offre FROM Postuler");
        return $requete->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Compte le nombre de relations pour un utilisateur donné.
     *
     * @param int $id_user, the ID of the user for which to count relations
     * @return int The number of relations for the user
     */
    public function countRelationsByUser(int $id_user): int
    {
        $requete = $this->pdo->prepare("SELECT COUNT(*) FROM Postuler WHERE Id_utilisateur = :id_user");
        $requete->execute(['id_user' => $id_user]);
        return (int)$requete->fetchColumn();
    }

    /**
     * Compte le nombre de relations pour une offre donnée.
     *
     * @param int $id_offre, the ID of the offer for which to count relations
     * @return int The number of relations for the offer
     */
    public function countRelationsByOffer(int $id_offre): int
    {
        $requete = $this->pdo->prepare("SELECT COUNT(*) FROM Postuler WHERE Id_offre = :id_offre");
        $requete->execute(['id_offre' => $id_offre]);
        return (int)$requete->fetchColumn();
    }
}