/**
 * JavaScript pour la page de détail d'une offre
 */

document.addEventListener("DOMContentLoaded", () => {
    // Récupérer l'ID de l'offre depuis les data attributes
    const offreData = document.getElementById("offre-data");
    const offreId = offreData ? offreData.dataset.offreId : null;

    // Initialiser les listeners des boutons
    initializeButtons();

    /**
     * Initialiser les listeners pour les boutons
     */
    function initializeButtons() {
        const btnPostuler = document.getElementById("btn-postuler");
        const btnWishlist = document.getElementById("btn-wishlist");
        const btnPartager = document.getElementById("btn-partager");

        if (btnPostuler) {
            btnPostuler.addEventListener("click", handlePostuler);
        }

        if (btnWishlist) {
            btnWishlist.addEventListener("click", handleWishlist);
            checkWishlistStatus();
        }

        if (btnPartager) {
            btnPartager.addEventListener("click", handlePartager);
        }
    }

    /**
     * Gérer le clic sur le bouton Postuler
     */
    function handlePostuler() {
        console.log("Candidature pour l'offre:", offreId);
        
        // Vérifier si l'utilisateur est connecté
        if (!isUserLoggedIn()) {
            alert("Vous devez être connecté pour postuler à une offre.");
            window.location.href = "/profil"; // À adapter selon votre route
            return;
        }

        // Afficher un message de confirmation
        const confirmed = confirm("Êtes-vous sûr de vouloir postuler à cette offre ?");
        if (!confirmed) return;

        // Envoyer la candidature via AJAX
        submitCandidacy();
    }

    /**
     * Soumettre la candidature
     */
    function submitCandidacy() {
        const btn = document.getElementById("btn-postuler");
        const originalText = btn.textContent;

        // État de chargement
        btn.disabled = true;
        btn.textContent = "Envoi en cours...";
        btn.classList.add("offre-loading");

        // Simuler l'envoi (à remplacer par un vrai appel API)
        setTimeout(() => {
            // En production, ceci serait un vrai appel fetch/AJAX
            console.log("Candidature envoyée pour l'offre:", offreId);
            
            btn.disabled = false;
            btn.classList.remove("offre-loading");
            btn.textContent = "Candidature envoyée !";
            btn.style.backgroundColor = "#28a745";

            // Afficher un message de succès
            showNotification("Votre candidature a été envoyée avec succès !", "success");

            // Réinitialiser le bouton après 3 secondes
            setTimeout(() => {
                btn.textContent = originalText;
                btn.style.backgroundColor = "";
            }, 3000);
        }, 800);
    }

    /**
     * Gérer l'ajout/retrait de la wishlist
     */
    function handleWishlist() {
        const btn = document.getElementById("btn-wishlist");
        
        // Vérifier si l'utilisateur est connecté
        if (!isUserLoggedIn()) {
            alert("Vous devez être connecté pour ajouter une offre à votre wishlist.");
            window.location.href = "/profil"; // À adapter selon votre route
            return;
        }

        // Basculer l'état
        if (btn.classList.contains("active")) {
            removeFromWishlist();
        } else {
            addToWishlist();
        }
    }

    /**
     * Ajouter à la wishlist
     */
    function addToWishlist() {
        const btn = document.getElementById("btn-wishlist");
        btn.disabled = true;
        btn.classList.add("offre-loading");

        setTimeout(() => {
            btn.classList.add("active");
            btn.textContent = "❤️ Retirer de la wishlist";
            btn.disabled = false;
            btn.classList.remove("offre-loading");
            
            showNotification("Offre ajoutée à votre wishlist !", "success");
            console.log("Offre", offreId, "ajoutée à la wishlist");
        }, 500);
    }

    /**
     * Retirer de la wishlist
     */
    function removeFromWishlist() {
        const btn = document.getElementById("btn-wishlist");
        btn.disabled = true;
        btn.classList.add("offre-loading");

        setTimeout(() => {
            btn.classList.remove("active");
            btn.textContent = "❤️ Ajouter à la wishlist";
            btn.disabled = false;
            btn.classList.remove("offre-loading");
            
            showNotification("Offre retirée de votre wishlist.", "info");
            console.log("Offre", offreId, "retirée de la wishlist");
        }, 500);
    }

    /**
     * Vérifier l'état de la wishlist
     */
    function checkWishlistStatus() {
        // Récupérer l'état depuis localStorage (simple implémentation)
        const wishlist = JSON.parse(localStorage.getItem("wishlist") || "[]");
        const btn = document.getElementById("btn-wishlist");

        if (wishlist.includes(parseInt(offreId))) {
            btn.classList.add("active");
            btn.textContent = "❤️ Retirer de la wishlist";
        }
    }

    /**
     * Gérer le partage
     */
    function handlePartager() {
        const url = window.location.href;
        const titre = "Offre intéressante sur Web4All";

        // Vérifier si l'API Web Share est disponible
        if (navigator.share) {
            navigator.share({
                title: titre,
                text: "Découvrez cette offre sur Web4All",
                url: url,
            }).catch((err) => console.log("Erreur lors du partage:", err));
        } else {
            // Fallback : copier le lien dans le presse-papiers
            copyToClipboard(url);
        }
    }

    /**
     * Copier le lien dans le presse-papiers
     */
    function copyToClipboard(text) {
        // Utiliser l'API moderne
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                showNotification("Lien copié dans le presse-papiers !", "success");
                console.log("Lien copié:", text);
            }).catch((err) => {
                console.error("Erreur lors de la copie:", err);
                fallbackCopyToClipboard(text);
            });
        } else {
            fallbackCopyToClipboard(text);
        }
    }

    /**
     * Fallback pour copier le lien (ancienne méthode)
     */
    function fallbackCopyToClipboard(text) {
        const textarea = document.createElement("textarea");
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand("copy");
            showNotification("Lien copié dans le presse-papiers !", "success");
        } catch (err) {
            console.error("Impossible de copier le lien:", err);
            showNotification("Erreur lors de la copie du lien.", "error");
        }
        document.body.removeChild(textarea);
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    function isUserLoggedIn() {
        // À adapter selon votre système d'authentification
        // Ceci est une implémentation simple
        return localStorage.getItem("user") !== null || 
               sessionStorage.getItem("user") !== null ||
               document.querySelector("[data-user-id]") !== null;
    }

    /**
     * Afficher une notification
     */
    function showNotification(message, type = "info") {
        // Créer l'élément de notification
        const notif = document.createElement("div");
        notif.className = `offre-${type}`;
        notif.textContent = message;
        notif.style.position = "fixed";
        notif.style.top = "20px";
        notif.style.right = "20px";
        notif.style.padding = "15px 20px";
        notif.style.borderRadius = "8px";
        notif.style.boxShadow = "0 2px 8px rgba(0, 0, 0, 0.1)";
        notif.style.zIndex = "9999";
        notif.style.animation = "slideInRight 0.3s ease";
        notif.style.fontWeight = "600";

        // Définir les couleurs selon le type
        if (type === "success") {
            notif.style.backgroundColor = "#d4edda";
            notif.style.color = "#155724";
            notif.style.border = "1px solid #28a745";
        } else if (type === "error") {
            notif.style.backgroundColor = "#f8d7da";
            notif.style.color = "#721c24";
            notif.style.border = "1px solid #dc3545";
        } else {
            notif.style.backgroundColor = "#d1ecf1";
            notif.style.color = "#0c5460";
            notif.style.border = "1px solid #17a2b8";
        }

        document.body.appendChild(notif);

        // Retirer après 4 secondes
        setTimeout(() => {
            notif.style.animation = "fadeOut 0.3s ease";
            setTimeout(() => {
                document.body.removeChild(notif);
            }, 300);
        }, 4000);
    }

    /**
     * Ajouter une animation de fade out
     */
    const style = document.createElement("style");
    style.textContent = `
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100px);
            }
        }
    `;
    document.head.appendChild(style);
});
