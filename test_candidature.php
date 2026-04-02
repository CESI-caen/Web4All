<?php
/**
 * Script de test pour vérifier que la candidature fonction
 * À exécuter une fois les migrations de BD appliquées
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Service/PdoService.php';
require_once __DIR__ . '/src/Model/CandidatureModel.php';

use App\Service\PdoService;
use App\Model\CandidatureModel;

try {
    $pdoService = new PdoService();
    $candidatureModel = new CandidatureModel($pdoService);

    // Test: Ajouter une candidature
    echo "Testing CandidatureModel...\n";
    
    $userId = 1;
    $offerId = 1;
    $motivation = "Je suis très intéressé par cette offre car elle correspond parfaitement à mes compétences et mes objectifs professionnels. Je suis convaincu que je pourrais apporter une valeur importante à votre entreprise.";

    // Vérifier si la candidature existe déjà
    if ($candidatureModel->hasAlreadyApplied($userId, $offerId)) {
        echo "❌ L'utilisateur a déjà postulé à cette offre\n";
    } else {
        // Ajouter la candidature
        if ($candidatureModel->addCandidature($userId, $offerId, $motivation)) {
            echo "✅ Candidature ajoutée avec succès!\n";
            
            // Récupérer les candidatures de l'utilisateur
            $userCandidatures = $candidatureModel->getCandidaturesByUser($userId);
            echo "Candidatures de l'utilisateur $userId:\n";
            foreach ($userCandidatures as $c) {
                echo "  - Offre: " . $c['nom_offre'] . " (ID: " . $c['Id_offre'] . ")\n";
                echo "    Statut: " . $c['Statut'] . "\n";
                echo "    Date: " . $c['Date_candidature'] . "\n";
            }
        } else {
            echo "❌ Erreur lors de l'ajout de la candidature\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
?>
