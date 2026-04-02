<?php
namespace App\Model;

use App\Service\PdoService;

class CandidatureModel
{
    private \PDO $pdo;

    public function __construct(PdoService $pdoService)
    {
        $this->pdo = $pdoService->getPdo();
    }

    /**
     * Ajouter une candidature
     *
     * @param int $userId L'ID de l'utilisateur
     * @param int $offerId L'ID de l'offre
     * @param string $motivation La lettre de motivation
     * @return bool True si succès, false sinon
     */
    public function addCandidature(int $userId, int $offerId, string $motivation): bool
    {
        try {
            $requete = $this->pdo->prepare(
                "INSERT INTO Postuler (Id_utilisateur, Id_offre, Lettre_motivation, Date_candidature, Statut) 
                 VALUES (:user_id, :offer_id, :motivation, NOW(), 'En attente')"
            );
            
            return $requete->execute([
                'user_id' => $userId,
                'offer_id' => $offerId,
                'motivation' => $motivation
            ]);
        } catch (\Exception $e) {
            error_log('Erreur lors de l\'ajout de candidature: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier si un utilisateur a déjà postulé à une offre
     *
     * @param int $userId L'ID de l'utilisateur
     * @param int $offerId L'ID de l'offre
     * @return bool True si déjà postulé, false sinon
     */
    public function hasAlreadyApplied(int $userId, int $offerId): bool
    {
        $requete = $this->pdo->prepare(
            "SELECT COUNT(*) as count FROM Postuler WHERE Id_utilisateur = :user_id AND Id_offre = :offer_id"
        );
        $requete->execute([
            'user_id' => $userId,
            'offer_id' => $offerId
        ]);
        
        $result = $requete->fetch();
        return $result['count'] > 0;
    }

    /**
     * Récupérer une candidature spécifique
     *
     * @param int $userId L'ID de l'utilisateur
     * @param int $offerId L'ID de l'offre
     * @return array|false Les données de la candidature si trouvée
     */
    public function getCandidature(int $userId, int $offerId): array|false
    {
        $requete = $this->pdo->prepare(
            "SELECT * FROM Postuler WHERE Id_utilisateur = :user_id AND Id_offre = :offer_id"
        );
        $requete->execute([
            'user_id' => $userId,
            'offer_id' => $offerId
        ]);
        
        return $requete->fetch();
    }

    /**
     * Récupérer toutes les candidatures d'un utilisateur
     *
     * @param int $userId L'ID de l'utilisateur
     * @return array Liste des candidatures
     */
    public function getCandidaturesByUser(int $userId): array
    {
        $requete = $this->pdo->prepare(
            "SELECT p.*, o.Nom as nom_offre, e.Nom as nom_entreprise 
             FROM Postuler p 
             JOIN Offres o ON p.Id_offre = o.Id_offre 
             JOIN Entreprises e ON o.Id_entreprise = e.Id_entreprise 
             WHERE p.Id_utilisateur = :user_id 
             ORDER BY p.Date_candidature DESC"
        );
        $requete->execute(['user_id' => $userId]);
        
        return $requete->fetchAll();
    }

    /**
     * Récupérer toutes les candidatures pour une offre
     *
     * @param int $offerId L'ID de l'offre
     * @return array Liste des candidatures
     */
    public function getCandidaturesByOffer(int $offerId): array
    {
        $requete = $this->pdo->prepare(
            "SELECT p.*, u.Prenom, u.Nom FROM Postuler p 
             JOIN Utilisateurs u ON p.Id_utilisateur = u.Id_utilisateur 
             WHERE p.Id_offre = :offer_id 
             ORDER BY p.Date_candidature DESC"
        );
        $requete->execute(['offer_id' => $offerId]);
        
        return $requete->fetchAll();
    }

    /**
     * Mettre à jour le statut d'une candidature
     *
     * @param int $userId L'ID de l'utilisateur
     * @param int $offerId L'ID de l'offre
     * @param string $statut Le nouveau statut
     * @return bool True si succès
     */
    public function updateStatut(int $userId, int $offerId, string $statut): bool
    {
        $requete = $this->pdo->prepare(
            "UPDATE Postuler SET Statut = :statut WHERE Id_utilisateur = :user_id AND Id_offre = :offer_id"
        );
        
        return $requete->execute([
            'statut' => $statut,
            'user_id' => $userId,
            'offer_id' => $offerId
        ]);
    }
}
