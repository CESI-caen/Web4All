<?php
namespace App\Model;

use App\Service\PdoService;

class ExigerModel
{
    private \PDO $pdo;

    public function __construct(PdoService $pdoService)
    {
        $this->pdo = $pdoService->getPdo();
    }

    /** Add a new relation between an offer and a competence
     *
     * @param int $idOffre The ID of the offer
     * @param int $idCompetence The ID of the competence
     * @return bool True if the insertion was successful, false otherwise
     */
    public function addRelation(int $idOffre, int $idCompetence): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Exiger (Id_offre, Id_competence) VALUES (:id_offre, :id_competence)");
        return $requete->execute(['id_offre' => $idOffre, 'id_competence' => $idCompetence]);
    }

    /** Check if a relation between an offer and a competence exists
     *
     * @param int $idOffre The ID of the offer
     * @param int $idCompetence The ID of the competence
     * @return bool True if the relation exists, false otherwise
     */
    public function relationExists(int $idOffre, int $idCompetence): bool
    {
        $requete = $this->pdo->prepare("SELECT 1 FROM Exiger WHERE Id_offre = :id_offre AND Id_competence = :id_competence");
        $requete->execute(['id_offre' => $idOffre, 'id_competence' => $idCompetence]);
        return $requete->fetch() !== false;
    }

    /** Delete a relation between an offer and a competence
     *
     * @param int $idOffre The ID of the offer
     * @param int $idCompetence The ID of the competence
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteRelation(int $idOffre, int $idCompetence): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Exiger WHERE Id_offre = :id_offre AND Id_competence = :id_competence");
        return $requete->execute(['id_offre' => $idOffre, 'id_competence' => $idCompetence]);
    }

    /** Delete all relations for a given offer
     *
     * @param int $idOffre The ID of the offer
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteAllRelationsByOffer(int $idOffre): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Exiger WHERE Id_offre = :id_offre");
        return $requete->execute(['id_offre' => $idOffre]);
    }

    /** Delete all relations for a given competence
     *
     * @param int $idCompetence The ID of the competence
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteAllRelationsByCompetence(int $idCompetence): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Exiger WHERE Id_competence = :id_competence");
        return $requete->execute(['id_competence' => $idCompetence]);
    }

    /** Get all competences for a given offer
     *
     * @param int $idOffre The ID of the offer
     * @return array The list of competences
     */
    public function getCompetencesByOffer(int $idOffre): array
    {
        $requete = $this->pdo->prepare("SELECT c.* FROM Competences c 
                                      INNER JOIN Exiger e ON c.Id_competence = e.Id_competence 
                                      WHERE e.Id_offre = :id_offre");
        $requete->execute(['id_offre' => $idOffre]);
        return $requete->fetchAll();
    }

    /** Get all offers for a given competence
     *
     * @param int $idCompetence The ID of the competence
     * @return array The list of offers
     */
    public function getOffersByCompetence(int $idCompetence): array
    {
        $requete = $this->pdo->prepare("SELECT o.* FROM Offres o 
                                      INNER JOIN Exiger e ON o.Id_offre = e.Id_offre 
                                      WHERE e.Id_competence = :id_competence");
        $requete->execute(['id_competence' => $idCompetence]);
        return $requete->fetchAll();
    }

    /** Get all relations
     *
     * @return array The list of relations
     */
    public function getAllRelations(): array
    {
        $requete = $this->pdo->query("SELECT * FROM Exiger");
        return $requete->fetchAll();
    }
}