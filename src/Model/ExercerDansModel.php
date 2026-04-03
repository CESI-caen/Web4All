<?php
namespace App\Model;

use App\Service\PdoService;

class ExercerDansModel
{
    private \PDO $pdo;

    public function __construct(PdoService $pdoService)
    {
        $this->pdo = $pdoService->getPdo();
    }

    /**
     * Récupère toutes les relations de la table Exercer_dans.
     *
     * @return array
     */
    public function getAllRelations(): array
    {
        $requete = $this->pdo->query("SELECT * FROM Exercer_dans");
        return $requete->fetchAll();
    }

    /**
     * Récupère les domaines d'une entreprise.
     *
     * @param int $idEntreprise
     * @return array
     */
    public function getDomainesByEnterprise(int $idEntreprise): array
    {
        $requete = $this->pdo->prepare("SELECT d.* FROM Domaines d 
                                      INNER JOIN Exercer_dans e ON d.Id_domaine = e.Id_domaine 
                                      WHERE e.Id_entreprise = :id_entreprise");
        $requete->execute(['id_entreprise' => $idEntreprise]);
        return $requete->fetchAll();
    }

    /**
     * Récupère les entreprises d'un domaine.
     *
     * @param int $idDomaine
     * @return array
     */
    public function getEnterprisesByDomain(int $idDomaine): array
    {
        $requete = $this->pdo->prepare("SELECT ent.* FROM Entreprises ent 
                                      INNER JOIN Exercer_dans e ON ent.Id_entreprise = e.Id_entreprise 
                                      WHERE e.Id_domaine = :id_domaine");
        $requete->execute(['id_domaine' => $idDomaine]);
        return $requete->fetchAll();
    }

    /**
     * Ajoute une relation dans la table Exercer_dans.
     *
     * @param int $idEntreprise
     * @param int $idDomaine
     * @return bool
     */
    public function addRelation(int $idEntreprise, int $idDomaine): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Exercer_dans (Id_entreprise, Id_domaine) VALUES (:id_entreprise, :id_domaine)");
        return $requete->execute(['id_entreprise' => $idEntreprise, 'id_domaine' => $idDomaine]);
    }

    /**
     * Vérifie si une relation existe dans la table Exercer_dans.
     *
     * @param int $idEntreprise
     * @param int $idDomaine
     * @return bool
     */
    public function relationExists(int $idEntreprise, int $idDomaine): bool
    {
        $requete = $this->pdo->prepare("SELECT 1 FROM Exercer_dans WHERE Id_entreprise = :id_entreprise AND Id_domaine = :id_domaine");
        $requete->execute(['id_entreprise' => $idEntreprise, 'id_domaine' => $idDomaine]);
        return $requete->fetch() !== false;
    }

    /**
     * Supprime une relation de la table Exercer_dans.
     *
     * @param int $idEntreprise
     * @param int $idDomaine
     * @return bool
     */
    public function deleteRelation(int $idEntreprise, int $idDomaine): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Exercer_dans WHERE Id_entreprise = :id_entreprise AND Id_domaine = :id_domaine");
        return $requete->execute(['id_entreprise' => $idEntreprise, 'id_domaine' => $idDomaine]);
    }

    /**
     * Supprime toutes les relations d'une entreprise de la table Exercer_dans.
     *
     * @param int $idEntreprise
     * @return bool
     */
    public function deleteAllRelationsByEnterprise(int $idEntreprise): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Exercer_dans WHERE Id_entreprise = :id_entreprise");
        return $requete->execute(['id_entreprise' => $idEntreprise]);
    }

    /**
     * Supprime toutes les relations d'un domaine de la table Exercer_dans.
     *
     * @param int $idDomaine
     * @return bool
     */
    public function deleteAllRelationsByDomain(int $idDomaine): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Exercer_dans WHERE Id_domaine = :id_domaine");
        return $requete->execute(['id_domaine' => $idDomaine]);
    }
}
