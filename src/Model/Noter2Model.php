<?php
namespace App\Model;

use App\Service\PdoService;


// Modèle de la table Noter_2 = noter une entreprise, qui représente la relation entre un utilisateur et une entreprise accompagnée d'une note et d'un commentaire
class Noter2Model
{
    private \PDO $pdo;

    public function __construct(PdoService $pdoService)
    {
        $this->pdo = $pdoService->getPdo();
    }

    /** Add a new relation between a user and a company
     *
     * @param int $id_user The ID of the user
     * @param int $id_entreprise The ID of the company
     * @param int $note The note for the company
     * @param string $commentaire The comment for the company
     * @return bool True if the insertion was successful, false otherwise
     */
    public function addRelation(int $id_user, int $id_entreprise, int $note, string $commentaire): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Noter_2 (Id_utilisateur, Id_entreprise, Note, Commentaire) VALUES (:id_user, :id_entreprise, :note, :commentaire)");
        return $requete->execute(['id_user' => $id_user, 'id_entreprise' => $id_entreprise, 'note' => $note, 'commentaire' => $commentaire]);
    }

    /** Check if a relation between a user and a company exists
     *
     * @param int $id_user The ID of the user
     * @param int $id_entreprise The ID of the company
     * @return bool True if the relation exists, false otherwise
     */
    public function relationExists(int $id_user, int $id_entreprise): bool
    {
        $requete = $this->pdo->prepare("SELECT 1 FROM Noter_2 WHERE Id_utilisateur = :id_user AND Id_entreprise = :id_entreprise");
        $requete->execute(['id_user' => $id_user, 'id_entreprise' => $id_entreprise]);
        return $requete->fetch() !== false;
    }

    /** Delete a relation between a user and a company
     *
     * @param int $id_user The ID of the user
     * @param int $id_entreprise The ID of the company
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteRelation(int $id_user, int $id_entreprise): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Noter_2 WHERE Id_utilisateur = :id_user AND Id_entreprise = :id_entreprise");
        return $requete->execute(['id_user' => $id_user, 'id_entreprise' => $id_entreprise]);
    }

    /** Delete all relations for a given user
     *
     * @param int $id_user The ID of the user
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteAllRelationsByUser(int $id_user): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Noter_2 WHERE Id_utilisateur = :id_user");
        return $requete->execute(['id_user' => $id_user]);
    }

    /** Delete all relations for a givencompany
     *
     * @param int $id_entreprise The ID of the company
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteAllRelationsByCompany(int $id_entreprise): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Noter_2 WHERE Id_entreprise = :id_entreprise");
        return $requete->execute(['id_entreprise' => $id_entreprise]);
    }

    /** Get the note and comment for a specific user andcompany
     *
     * @param int $id_user The ID of the user
     * @param int $id_entreprise The ID of the company
     * @return array|null The note and comment, or null if not found
     */
    public function getNoteAndComment(int $id_user, int $id_entreprise): ?array
    {
        $requete = $this->pdo->prepare("SELECT Note, Commentaire FROM Noter_2 WHERE Id_utilisateur = :id_user AND Id_entreprise = :id_entreprise");
        $requete->execute(['id_user' => $id_user, 'id_entreprise' => $id_entreprise]);
        return $requete->fetch();
    }

    /** Get all relations
     *
     * @return array The list of all relations
     */
    public function getAllRelations(): array
    {
        $requete = $this->pdo->query("SELECT * FROM Noter_2");
        return $requete->fetchAll();
    }

    /** Get all notes and comments for a specificcompany
     *
     * @param int $id_entreprise The ID of the company
     * @return array The list of notes and comments
     */
    public function getNotesByCompany(int $id_entreprise): array
    {
        $requete = $this->pdo->prepare("SELECT * FROM Noter_2 WHERE Id_entreprise = :id_entreprise");
        $requete->execute(['id_entreprise' => $id_entreprise]);
        return $requete->fetchAll();
    }

    /** Get all notes and comments for a specific user
     *
     * @param int $id_user The ID of the user
     * @return array The list of notes and comments
     */
    public function getNotesByUser(int $id_user): array
    {
        $requete = $this->pdo->prepare("SELECT * FROM Noter_2 WHERE Id_utilisateur = :id_user");
        $requete->execute(['id_user' => $id_user]);
        return $requete->fetchAll();
    }

    /** Update the note and comment for a specific user andcompany
     *
     * @param int $id_user The ID of the user
     * @param int $id_entreprise The ID of the company
     * @param int $note The new note
     * @param string $commentaire The new comment
     * @return bool True if the update was successful, false otherwise
     */
    public function updateNoteAndComment(int $id_user, int $id_entreprise, int $note, string $commentaire): bool
    {
        $requete = $this->pdo->prepare("UPDATE Noter_2 SET Note = :note, Commentaire = :commentaire WHERE Id_utilisateur = :id_user AND Id_entreprise = :id_entreprise");
        return $requete->execute(['note' => $note, 'commentaire' => $commentaire, 'id_user' => $id_user, 'id_entreprise' => $id_entreprise]);
    }

    /** Get the average note for a specificcompany
     *
     * @param int $id_entreprise The ID of the company
     * @return float|null The average note, or null if no notes are found
     */
    public function getAverageNoteByCompany(int $id_entreprise): ?float
    {
        $requete = $this->pdo->prepare("SELECT AVG(Note) as average_note FROM Noter_2 WHERE Id_entreprise = :id_entreprise");
        $requete->execute(['id_entreprise' => $id_entreprise]);
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
        $requete = $this->pdo->prepare("SELECT AVG(Note) as average_note FROM Noter_2 WHERE Id_utilisateur = :id_user");
        $requete->execute(['id_user' => $id_user]);
        $result = $requete->fetch();
        return $result['average_note'] !== null ? (float)$result['average_note'] : null;
    }

    /** Count the number of notes for a specificcompany
     *
     * @param int $id_entreprise The ID of the company
     * @return int The number of notes
     */
    public function countNotesByCompany(int $id_entreprise): int
    {
        $requete = $this->pdo->prepare("SELECT COUNT(*) as note_count FROM Noter_2 WHERE Id_entreprise = :id_entreprise");
        $requete->execute(['id_entreprise' => $id_entreprise]);
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
        $requete = $this->pdo->prepare("SELECT COUNT(*) as note_count FROM Noter_2 WHERE Id_utilisateur = :id_user");
        $requete->execute(['id_user' => $id_user]);
        $result = $requete->fetch();
        return (int)$result['note_count'];
    }
}