<?php
// #######################################################################
// Model pour la table 'Compétences'
// __________ MLD __________
// -> Id_competence : BIGINT AUTO-INCREMENT PRIMARY-KEY
// -> Nom : VARCHAR(50)
// #######################################################################

namespace App\Model;

// Inclut le fichier qui gère l'objet pdo, pour la connection à la bdd
use App\Service\PdoService;

class CompetencesModel {
    /**
     * Database connection instance
     *
     * @var \PDO A PHP Data Objects instance used for database operations
     */
    private \PDO $pdo;

    /**
     * Constructor 
     *
     * @param PdoService $pdoService The object responsible of the connection with the database
     */
    public function __construct(PdoService $pdoService) {
        $this->pdo = $pdoService->getPdo();
    }

    /**
     * Add a new skill (competence)
     * 
     * @param string $name
     */
    public function addSkill(string $name) {
        $request = $this->pdo->prepare("INSERT INTO Competences (Nom) VALUES (:name)");
        $request->execute(['name' => $name]);
    }

    /**
     * Get all skills (competences)
     * 
     * @return array An array of all skills
     */
    public function getAllSkills(): array {
        $request = $this->pdo->query("SELECT * FROM Competences");
        return $request->fetchAll();
    }

    /**
     * Get a skill by its ID
     * 
     * @param int $id The ID of the skill
     * @return array|false An array containing the skill data or false if not found
     */
    public function getSkillById(int $id): array|false {
        $request = $this->pdo->prepare("SELECT * FROM Competences WHERE Id_competence = :id");
        $request->execute(['id' => $id]);
        return $request->fetch();
    }

    /**
     * Get a skill's id with name
     * 
     * @param string $name The name of the skill
     * @return int|false The ID of the skill or false if not found
     */
    public function getSkillIdByName(string $name): int|false {
        $request = $this->pdo->prepare("SELECT Id_competence FROM Competences WHERE Nom = :name");
        $request->execute(['name' => $name]);
        $result = $request->fetch();
        return $result ? (int)$result['Id_competence'] : false;
    }

    /**
     * Delete a skill by its ID
     * 
     * @param int $id The ID of the skill to delete
     */
    public function deleteSkill(int $id) {
        $request = $this->pdo->prepare("DELETE FROM Competences WHERE Id_competence = :id");
        $request->execute(['id' => $id]);
    }
}