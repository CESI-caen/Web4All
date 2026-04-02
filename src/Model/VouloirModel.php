<?php
namespace App\Model;

use App\Service\PdoService;

class VouloirModel
{
    private \PDO $pdo;

    public function __construct(PdoService $pdoService)
    {
        $this->pdo = $pdoService->getPdo();
    }

    /** Add a new relation between a user and an offer
     *
     * @param int $id_user The ID of the user
     * @param int $id_offre The ID of the offer
     * @return bool True if the insertion was successful, false otherwise
     */
    public function addRelation(int $id_user, int $id_offre): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Vouloir (Id_utilisateur, Id_offre) VALUES (:id_user, :id_offre)");
        return $requete->execute(['id_user' => $id_user, 'id_offre' => $id_offre]);
    }

    /** Check if a relation between a user and an offer exists
     *
     * @param int $id_user The ID of the user
     * @param int $id_offre The ID of the offer
     * @return bool True if the relation exists, false otherwise
     */
    public function relationExists(int $id_user, int $id_offre): bool
    {
        $requete = $this->pdo->prepare("SELECT 1 FROM Vouloir WHERE Id_utilisateur = :id_user AND Id_offre = :id_offre");
        $requete->execute(['id_user' => $id_user, 'id_offre' => $id_offre]);
        return $requete->fetch() !== false;
    }

    /** Delete a relation between a user and an offer
     *
     * @param int $id_user The ID of the user
     * @param int $id_offre The ID of the offer
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteRelation(int $id_user, int $id_offre): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Vouloir WHERE Id_utilisateur = :id_user AND Id_offre = :id_offre");
        return $requete->execute(['id_user' => $id_user, 'id_offre' => $id_offre]);
    }

    /** Delete all relations for a specific user
     *
     * @param int $id_user The ID of the user
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteAllRelationsByUser(int $id_user): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Vouloir WHERE Id_utilisateur = :id_user");
        return $requete->execute(['id_user' => $id_user]);
    }

    /** Delete all relations for a specific offer
     *
     * @param int $id_offre The ID of the offer
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteAllRelationsByOffre(int $id_offre): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Vouloir WHERE Id_offre = :id_offre");
        return $requete->execute(['id_offre' => $id_offre]);
    }

    /** Get all offers for a specific user
     *
     * @param int $id_user The ID of the user
     * @return array The list of offers
     */
    public function getOffresByUser(int $id_user): array
    {
        $requete = $this->pdo->prepare("SELECT o.* FROM Offres o 
                                      INNER JOIN Vouloir v ON o.Id_offre = v.Id_offre 
                                      WHERE v.Id_utilisateur = :id_user");
        $requete->execute(['id_user' => $id_user]);
        return $requete->fetchAll();
    }

    /** Get all users for a specific offer
     *
     * @param int $id_offre The ID of the offer
     * @return array The list of users
     */
    public function getUsersByOffre(int $id_offre): array
    {
        $requete = $this->pdo->prepare("SELECT u.* FROM Utilisateurs u 
                                      INNER JOIN Vouloir v ON u.Id_utilisateur = v.Id_utilisateur 
                                      WHERE v.Id_offre = :id_offre");
        $requete->execute(['id_offre' => $id_offre]);
        return $requete->fetchAll();
    }

    /** Get all relations
     *
     * @return array The list of relations
     */
    public function getAllRelations(): array
    {
        $requete = $this->pdo->query("SELECT * FROM Vouloir");
        return $requete->fetchAll();
    }

    /** Count the number of users for a specific offer
     *
     * @param int $id_offre The ID of the offer
     * @return int The count of users
     */
    public function countUsersByOffre(int $id_offre): int
    {
        $requete = $this->pdo->prepare("SELECT COUNT(*) FROM Vouloir WHERE Id_offre = :id_offre");
        $requete->execute(['id_offre' => $id_offre]);
        return (int)$requete->fetchColumn();
    }

    /** Count the number of offers for a specific user
     *
     * @param int $id_user The ID of the user
     * @return int The count of offers
     */
    public function countOffresByUser(int $id_user): int
    {
        $requete = $this->pdo->prepare("SELECT COUNT(*) FROM Vouloir WHERE Id_utilisateur = :id_user");
        $requete->execute(['id_user' => $id_user]);
        return (int)$requete->fetchColumn();
    }

    // methodes pour whishlists
    public function getWishLists(int $id_user): array
    {
        $requete = $this->pdo->prepare("SELECT Vouloir.*, Offres.Nom AS Nom_Offre, Entreprises.Nom AS Nom_Entreprise, Utilisateurs.Nom AS Nom_Utilisateur, Utilisateurs.Prenom AS Prenom_Utilisateur FROM Vouloir
                                        JOIN Offres ON Offres.Id_offre = Vouloir.Id_offre
                                        JOIN Entreprises ON Offres.Id_entreprise = Entreprises.Id_entreprise
                                        JOIN Utilisateurs On Utilisateurs.Id_utilisateur = Entreprises.Id_utilisateur
                                        WHERE Vouloir.Id_utilisateur = :id_user");
        $requete->execute(['id_user' => $id_user]);
        return $requete->fetchAll();
    }
}