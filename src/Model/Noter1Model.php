<?php
namespace App\Model;

use App\Service\PdoService;


// Modèle de la table Noter_1 = noter une offre, qui représente la relation entre un utilisateur et une offre accompagnée d'une note et d'un commentaire
class Noter1Model
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
     * @param int $note The note for the offer
     * @param string $commentaire The comment for the offer
     * @return bool True if the insertion was successful, false otherwise
     */
    public function addRelation(int $id_user, int $id_offre, int $note, string $commentaire): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Noter_1 (Id_utilisateur, Id_offre, Note, Commentaire) VALUES (:id_user, :id_offre, :note, :commentaire)");
        return $requete->execute(['id_user' => $id_user, 'id_offre' => $id_offre, 'note' => $note, 'commentaire' => $commentaire]);
    }

    /** Check if a relation between a user and an offer exists
     *
     * @param int $id_user The ID of the user
     * @param int $id_offre The ID of the offer
     * @return bool True if the relation exists, false otherwise
     */
    public function relationExists(int $id_user, int $id_offre): bool
    {
        $requete = $this->pdo->prepare("SELECT 1 FROM Noter_1 WHERE Id_utilisateur = :id_user AND Id_offre = :id_offre");
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
        $requete = $this->pdo->prepare("DELETE FROM Noter_1 WHERE Id_utilisateur = :id_user AND Id_offre = :id_offre");
        return $requete->execute(['id_user' => $id_user, 'id_offre' => $id_offre]);
    }

    /** Delete all relations for a given user
     *
     * @param int $id_user The ID of the user
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteAllRelationsByUser(int $id_user): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Noter_1 WHERE Id_utilisateur = :id_user");
        return $requete->execute(['id_user' => $id_user]);
    }

    /** Delete all relations for a given offer
     *
     * @param int $id_offre The ID of the offer
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteAllRelationsByOffer(int $id_offre): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Noter_1 WHERE Id_offre = :id_offre");
        return $requete->execute(['id_offre' => $id_offre]);
    }

    /** Get the note and comment for a specific user and offer
     *
     * @param int $id_user The ID of the user
     * @param int $id_offre The ID of the offer
     * @return array|null The note and comment, or null if not found
     */
    public function getNoteAndComment(int $id_user, int $id_offre): ?array
    {
        $requete = $this->pdo->prepare("SELECT Note, Commentaire FROM Noter_1 WHERE Id_utilisateur = :id_user AND Id_offre = :id_offre");
        $requete->execute(['id_user' => $id_user, 'id_offre' => $id_offre]);
        return $requete->fetch();
    }

    /** Get all relations
     *
     * @return array The list of all relations
     */
    public function getAllRelations(): array
    {
        $requete = $this->pdo->query("SELECT * FROM Noter_1");
        return $requete->fetchAll();
    }

    /** Get all notes and comments for a specific offer
     *
     * @param int $id_offre The ID of the offer
     * @return array The list of notes and comments
     */
    public function getNotesByOffer(int $id_offre): array
    {
        $requete = $this->pdo->prepare("SELECT * FROM Noter_1 WHERE Id_offre = :id_offre");
        $requete->execute(['id_offre' => $id_offre]);
        return $requete->fetchAll();
    }

    /** Get all notes and comments for a specific user
     *
     * @param int $id_user The ID of the user
     * @return array The list of notes and comments
     */
    public function getNotesByUser(int $id_user): array
    {
        $requete = $this->pdo->prepare("SELECT * FROM Noter_1 WHERE Id_utilisateur = :id_user");
        $requete->execute(['id_user' => $id_user]);
        return $requete->fetchAll();
    }

    /** Update the note and comment for a specific user and offer
     *
     * @param int $id_user The ID of the user
     * @param int $id_offre The ID of the offer
     * @param int $note The new note
     * @param string $commentaire The new comment
     * @return bool True if the update was successful, false otherwise
     */
    public function updateNoteAndComment(int $id_user, int $id_offre, int $note, string $commentaire): bool
    {
        $requete = $this->pdo->prepare("UPDATE Noter_1 SET Note = :note, Commentaire = :commentaire WHERE Id_utilisateur = :id_user AND Id_offre = :id_offre");
        return $requete->execute(['note' => $note, 'commentaire' => $commentaire, 'id_user' => $id_user, 'id_offre' => $id_offre]);
    }

    /** Get the average note for a specific offer
     *
     * @param int $id_offre The ID of the offer
     * @return float|null The average note, or null if no notes are found
     */
    public function getAverageNoteByOffer(int $id_offre): ?float
    {
        $requete = $this->pdo->prepare("SELECT AVG(Note) as average_note FROM Noter_1 WHERE Id_offre = :id_offre");
        $requete->execute(['id_offre' => $id_offre]);
        $result = $requete->fetch();
        return $result['average_note'] !== null ? (float)$result['average_note'] : null;
    }

    /** Get the average note for a specific user
     *
     * @param int $id_user The ID of the user
     * @return float|null The average note, or null if no notes are found
     */
    public function getAverageNoteByUser(int $id_user): ?float
    {
        $requete = $this->pdo->prepare("SELECT AVG(Note) as average_note FROM Noter_1 WHERE Id_utilisateur = :id_user");
        $requete->execute(['id_user' => $id_user]);
        $result = $requete->fetch();
        return $result['average_note'] !== null ? (float)$result['average_note'] : null;
    }

    /** Count the number of notes for a specific offer
     *
     * @param int $id_offre The ID of the offer
     * @return int The number of notes
     */
    public function countNotesByOffer(int $id_offre): int
    {
        $requete = $this->pdo->prepare("SELECT COUNT(*) as note_count FROM Noter_1 WHERE Id_offre = :id_offre");
        $requete->execute(['id_offre' => $id_offre]);
        $result = $requete->fetch();
        return (int)$result['note_count'];
    }

    /** Count the number of notes for a specific user
     *
     * @param int $id_user The ID of the user
     * @return int The number of notes
     */
    public function countNotesByUser(int $id_user): int
    {
        $requete = $this->pdo->prepare("SELECT COUNT(*) as note_count FROM Noter_1 WHERE Id_utilisateur = :id_user");
        $requete->execute(['id_user' => $id_user]);
        $result = $requete->fetch();
        return (int)$result['note_count'];
    }
}