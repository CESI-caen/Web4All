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

    public function getAllRelations(): array
    {
        $requete = $this->pdo->query("SELECT * FROM Exercer_dans");
        return $requete->fetchAll();
    }

    public function getDomainesByEnterprise(int $idEntreprise): array
    {
        $requete = $this->pdo->prepare("SELECT d.* FROM Domaines d 
                                      INNER JOIN Exercer_dans e ON d.Id_domaine = e.Id_domaine 
                                      WHERE e.Id_entreprise = :id_entreprise");
        $requete->execute(['id_entreprise' => $idEntreprise]);
        return $requete->fetchAll();
    }

    public function getEnterprisesByDomain(int $idDomaine): array
    {
        $requete = $this->pdo->prepare("SELECT ent.* FROM Entreprises ent 
                                      INNER JOIN Exercer_dans e ON ent.Id_entreprise = e.Id_entreprise 
                                      WHERE e.Id_domaine = :id_domaine");
        $requete->execute(['id_domaine' => $idDomaine]);
        return $requete->fetchAll();
    }

    public function addRelation(int $idEntreprise, int $idDomaine): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Exercer_dans (Id_entreprise, Id_domaine) VALUES (:id_entreprise, :id_domaine)");
        return $requete->execute(['id_entreprise' => $idEntreprise, 'id_domaine' => $idDomaine]);
    }

    public function relationExists(int $idEntreprise, int $idDomaine): bool
    {
        $requete = $this->pdo->prepare("SELECT 1 FROM Exercer_dans WHERE Id_entreprise = :id_entreprise AND Id_domaine = :id_domaine");
        $requete->execute(['id_entreprise' => $idEntreprise, 'id_domaine' => $idDomaine]);
        return $requete->fetch() !== false;
    }

    public function deleteRelation(int $idEntreprise, int $idDomaine): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Exercer_dans WHERE Id_entreprise = :id_entreprise AND Id_domaine = :id_domaine");
        return $requete->execute(['id_entreprise' => $idEntreprise, 'id_domaine' => $idDomaine]);
    }

    public function deleteAllRelationsByEnterprise(int $idEntreprise): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Exercer_dans WHERE Id_entreprise = :id_entreprise");
        return $requete->execute(['id_entreprise' => $idEntreprise]);
    }

    public function deleteAllRelationsByDomain(int $idDomaine): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Exercer_dans WHERE Id_domaine = :id_domaine");
        return $requete->execute(['id_domaine' => $idDomaine]);
    }
}
