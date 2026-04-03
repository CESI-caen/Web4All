<?php
namespace App\Model;

use App\Service\PdoService;

class OffreModel
{
    private \PDO $pdo;

    /** Constructor
     *
     * @param PdoService $pdoService The object responsible of the connection with the database
     */
    public function __construct(PdoService $pdoService)
    {
        $this->pdo = $pdoService->getPdo();
    }

    /** Get all job offers
     *
     * @return array The list of all job offers
     */
    public function getAllOffres(): array
    {
        $requete = $this->pdo->query("SELECT * FROM Offres");
        return $requete->fetchAll();
    }

    /** Get all job offers for a specific user
     *
     * @param int $userId The ID of the user
     * @return array The list of job offers for the user
     */
    public function getAllOffresByUserId(int $userId): array
    {
        $requete = $this->pdo->prepare("SELECT * FROM Offres WHERE Id_entreprise IN (SELECT Id_entreprise FROM Entreprises WHERE Id_utilisateur = :user_id)");
        $requete->execute(['user_id' => $userId]);
        return $requete->fetchAll();
    }

    /** Get all job offers for a specific enterprise
     *
     * @param int $enterpriseId The ID of the enterprise
     * @return array The list of job offers for the enterprise
     */
    public function getOffresByEnterpriseId(int $enterpriseId): array
    {
        $requete = $this->pdo->prepare("SELECT * FROM Offres WHERE Id_entreprise = :enterprise_id");
        $requete->execute(['enterprise_id' => $enterpriseId]);
        return $requete->fetchAll();
    }

    /** Get a job offer by its ID
     *
     * @param int $id The ID of the offer
     * @return array|false The job offer if found, false otherwise
     */
    public function getOffreById(int $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT * FROM Offres WHERE Id_offre = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    /** Get a job offer by its ID
     *
     * @param int $id The ID of the offer
     * @return array|false The job offer if found, false otherwise
     */
    public function getOffreById_entreprise(int $id): array
    {
        $requete = $this->pdo->prepare("SELECT * FROM Offres WHERE Id_entreprise = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetchAll();
    }

    /** Get the name of a job offer by its ID
     *
     * @param string $id The ID of the offer
     * @return array|false The name of the offer if found, false otherwise
     */
    public function getNameById(string $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT Nom FROM Offres WHERE Id_offre = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    /** Get the description of a job offer by its ID
     *
     * @param string $id The ID of the offer
     * @return array|false The description of the offer if found, false otherwise
     */
    public function getDescriptionById(string $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT Descriptif FROM Offres WHERE Id_offre = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    /** Get the salary of a job offer by its ID
     *
     * @param string $id The ID of the offer
     * @return array|false The salary of the offer if found, false otherwise
     */
    public function getSalaryById(string $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT Salary FROM Offres WHERE Id_offre = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    public function getDurationById(string $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT Duration FROM Offres WHERE Id_offre = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    public function getDateStartById(string $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT Date_start FROM Offres WHERE Id_offre = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    public function getDateEndById(string $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT Date_end FROM Offres WHERE Id_offre = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    /** Get the company ID associated with a job offer by its ID
     *
     * @param string $id The ID of the offer
     * @return array|false The company ID if found, false otherwise
     */
    public function getEntrepriseIdByOffreId(string $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT Id_entreprise FROM Offres WHERE Id_offre = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    /**
     * Add a new offer (offre)
     * 
     * @param string $descriptif The description of the offer
     * @param string $date_start The start date of the offer
     * @param string $date_end The end date of the offer
     * @param int $duration The duration of the offer
     * @param float $salary The salary of the offer
     * @param int $id_entreprise The ID of the company offering the position
     * @return bool True if the insertion was successful, false otherwise
     */
    public function addOffre(string $nom,string $descriptif, string $date_start, string $date_end, int $duration, float $salary, int $id_entreprise): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Offres (Nom, Descriptif, Date_debut, Date_fin, Duree, Renumeration, Id_entreprise) VALUES (:nom, :descriptif, :date_start, :date_end, :duration, :salary, :id_entreprise)");
        return $requete->execute(['nom' => $nom, 'descriptif' => $descriptif, 'date_start' => $date_start, 'date_end' => $date_end, 'duration' => $duration, 'salary' => $salary, 'id_entreprise' => $id_entreprise ]);
    }

    /**
     * Update an existing offer (offre)
     * 
     * @param int $id The ID of the offer to update
     * @param string $description The new description of the offer
     * @param string $date_start The new start date of the offer
     * @param string $date_end The new end date of the offer
     * @param int $duration The new duration of the offer
     * @param float $salary The new salary of the offer
     * @return bool True if the update was successful, false otherwise
     */
    public function updateOffre(int $id,string $nom, string $description, string $date_start, string $date_end, int $duration, float $salary): bool // à completer avec request->request->get() ( string $descriptif, string $date_debut, string $date_fin, int $duree, float $renumeration)
    {
        $requete = $this->pdo->prepare("UPDATE Offres SET Nom = :nom, Descriptif = :description, Date_debut = :date_debut, Date_fin = :date_fin, Duree = :duration, Renumeration = :salary WHERE Id_offre = :id");
        return $requete->execute(['nom' => $nom, 'description' => $description, 'date_debut' => $date_start, 'date_fin' => $date_end, 'duration' => $duration, 'salary' => $salary, 'id' => $id]);
    }

    /** Update the name of a job offer by its ID
     *
     * @param int $id The ID of the offer to update
     * @param string $name The new name of the offer
     * @return bool True if the update was successful, false otherwise
     */
    public function updateOffreName(int $id, string $name): bool
    {
        $requete = $this->pdo->prepare("UPDATE Offres SET Nom = :name WHERE Id_offre = :id");
        return $requete->execute(['name' => $name, 'id' => $id]);
    }

    public function updateOffreDescription(int $id, string $description): bool
    {
        $requete = $this->pdo->prepare("UPDATE Offres SET Descriptif = :description WHERE Id_offre = :id");
        return $requete->execute(['description' => $description, 'id' => $id]);
    }

    public function updateOffreSalary(int $id, float $salary): bool
    {
        $requete = $this->pdo->prepare("UPDATE Offres SET Salary = :salary WHERE Id_offre = :id");
        return $requete->execute(['salary' => $salary, 'id' => $id]);
    }

    public function updateOffreDuration(int $id, int $duration): bool
    {
        $requete = $this->pdo->prepare("UPDATE Offres SET Duration = :duration WHERE Id_offre = :id");
        return $requete->execute(['duration' => $duration, 'id' => $id]);
    }

    public function updateOffreDateStart(int $id, string $date_start): bool
    {
        $requete = $this->pdo->prepare("UPDATE Offres SET Date_start = :date_start WHERE Id_offre = :id");
        return $requete->execute(['date_start' => $date_start, 'id' => $id]);
    }

    public function updateOffreDateEnd(int $id, string $date_end): bool
    {
        $requete = $this->pdo->prepare("UPDATE Offres SET Date_end = :date_end WHERE Id_offre = :id");
        return $requete->execute(['date_end' => $date_end, 'id' => $id]);
    }

    /**
     * Delete an existing offer (offre)
     * 
     * @param int $id The ID of the offer to delete
     * @return bool True if the delete was successful, false otherwise
     */
    public function deleteOffre(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Offres WHERE Id_offre = :id");
        return $requete->execute(['id' => $id]);
    }
}