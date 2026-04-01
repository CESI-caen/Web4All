/**
 * JavaScript pour la page de candidature
 * Vérifie d'abord si l'utilisateur a un CV
 */

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("form-candidature");
    const offreData = document.getElementById("offre-data");
    const offreId = offreData ? offreData.dataset.offreId : null;
    const formSection = document.querySelector(".postuler-form-section");

    // Vérifier d'abord si l'utilisateur a un CV
    checkUserCvStatus();

    /**
     * Vérifier si l'utilisateur a un CV
     */
    function checkUserCvStatus() {
        // Récupérer les données utilisateur depuis localStorage ou sessionStorage
        const userData = localStorage.getItem("userData") || sessionStorage.getItem("userData");
        const userLoggedIn = localStorage.getItem("user") || sessionStorage.getItem("user");
        
        if (!userLoggedIn || !userData) {
            // L'utilisateur n'est pas connecté
            redirectToLogin();
            return;
        }

        try {
            const user = JSON.parse(userData);
            const userId = user.id || user.userId;

            if (!userId) {
                redirectToLogin();
                return;
            }

            // Appeler l'endpoint pour vérifier le CV
            fetch("/api/check-cv", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ userId: userId }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error("Erreur:", data.error);
                    redirectToLogin();
                    return;
                }

                // Si l'utilisateur n'a pas de CV, afficher un message et rediriger
                if (!data.hasCv) {
                    showNoCvMessage();
                } else {
                    // L'utilisateur a un CV, initialiser le formulaire
                    initializeForm();
                    // Auto-remplir les données
                    populateUserData(data);
                }
            })
            .catch(error => {
                console.error("Erreur lors de la vérification du CV:", error);
                // En cas d'erreur, laisser continuer avec le formulaire
                initializeForm();
            });
        } catch (e) {
            console.log("Impossible de parser userData");
            initializeForm();
        }
    }

    /**
     * Afficher un message si l'utilisateur n'a pas de CV
     */
    function showNoCvMessage() {
        if (formSection) {
            formSection.innerHTML = `
                <div class="no-cv-warning">
                    <div class="warning-icon">⚠️</div>
                    <h2>CV manquant</h2>
                    <p>Pour postuler à cette offre, vous devez d'abord ajouter votre CV à votre compte.</p>
                    <a href="/cv" class="btn btn-primary">Ajouter votre CV</a>
                </div>
            `;
        }
    }

    /**
     * Rediriger vers la page de connexion
     */
    function redirectToLogin() {
        window.location.href = "/login";
    }

    /**
     * Peupler les données utilisateur dans le formulaire
     */
    function populateUserData(data) {
        if (form) {
            const prenom = document.getElementById("prenom");
            const nom = document.getElementById("nom");
            const email = document.getElementById("email");
            const telephone = document.getElementById("telephone");

            if (prenom && data.prenom) prenom.value = data.prenom;
            if (nom && data.nom) nom.value = data.nom;
            if (email && data.email) email.value = data.email;
            if (telephone && data.telephone) telephone.value = data.telephone;
        }
    }

    /**
     * Initialiser le formulaire
     */
    function initializeForm() {
        // Submission du formulaire
        if (form) {
            form.addEventListener("submit", handleFormSubmit);
        }

        // Auto-fill si l'utilisateur est connecté
        autoFillUserData();
    }

    /**
     * Gérer la soumission du formulaire
     */
    function handleFormSubmit(e) {
        e.preventDefault();

        // Valider le formulaire
        if (!validateForm()) {
            return;
        }

        // Soumettre
        submitForm();
    }

    /**
     * Valider le formulaire
     */
    function validateForm() {
        let isValid = true;

        // Réinitialiser les erreurs
        document.querySelectorAll(".form-error").forEach(error => error.textContent = "");
        document.querySelectorAll(".form-input, .form-textarea").forEach(input => {
            input.classList.remove("error", "valid");
        });

        // Prenom
        const prenom = document.getElementById("prenom").value.trim();
        if (!prenom) {
            showFieldError("prenom", "Le prénom est requis");
            isValid = false;
        }

        // Nom
        const nom = document.getElementById("nom").value.trim();
        if (!nom) {
            showFieldError("nom", "Le nom est requis");
            isValid = false;
        }

        // Email
        const email = document.getElementById("email").value.trim();
        if (!email || !isValidEmail(email)) {
            showFieldError("email", "Veuillez entrer un email valide");
            isValid = false;
        }

        // Téléphone (optionnel mais valider si rempli)
        const telephone = document.getElementById("telephone").value.trim();
        if (telephone && !isValidPhone(telephone)) {
            showFieldError("telephone", "Format de téléphone invalide");
            isValid = false;
        }

        // Lettre de motivation
        const lettre = document.getElementById("lettre").value.trim();
        if (!lettre || lettre.length < 50) {
            showFieldError("lettre", "La lettre doit faire au moins 50 caractères");
            isValid = false;
        }

        // Conditions
        const conditions = document.getElementById("conditions").checked;
        if (!conditions) {
            showFieldError("conditions", "Vous devez accepter les conditions");
            isValid = false;
        }

        return isValid;
    }

    /**
     * Soumettre le formulaire
     */
    function submitForm() {
        const submitBtn = document.querySelector(".btn-primary");
        const originalText = submitBtn.textContent;

        // État de chargement
        submitBtn.disabled = true;
        submitBtn.classList.add("btn-loading");
        form.classList.add("form-loading");

        // Créer les données du formulaire
        const formData = new FormData(form);
        formData.append("offre_id", offreId);

        // Envoyer le formulaire (simulation)
        setTimeout(() => {
            // En production, faire un vrai appel AJAX
            console.log("Candidature soumise pour l'offre", offreId);
            console.log("Données:", {
                prenom: formData.get("prenom"),
                nom: formData.get("nom"),
                email: formData.get("email"),
                telephone: formData.get("telephone"),
                lettre: formData.get("lettre"),
            });

            // Afficher le message de succès
            form.style.display = "none";
            document.getElementById("success-message").style.display = "block";

            // Réinitialiser le bouton
            submitBtn.disabled = false;
            submitBtn.classList.remove("btn-loading");
            submitBtn.textContent = originalText;
        }, 1500);
    }

    /**
     * Auto-remplir avec les données de l'utilisateur connecté
     */
    function autoFillUserData() {
        // Récupérer les données depuis localStorage ou sessionStorage
        const userData = localStorage.getItem("userData") || sessionStorage.getItem("userData");
        
        if (userData) {
            try {
                const user = JSON.parse(userData);
                if (user.prenom) document.getElementById("prenom").value = user.prenom;
                if (user.nom) document.getElementById("nom").value = user.nom;
                if (user.email) document.getElementById("email").value = user.email;
                if (user.telephone) document.getElementById("telephone").value = user.telephone;
            } catch (e) {
                console.log("Impossible de parser userData");
            }
        }
    }

    /**
     * Valider un email
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    /**
     * Valider un téléphone
     */
    function isValidPhone(phone) {
        const phoneRegex = /^[+]?[(]?[0-9]{3}[)]?[-\s]?[0-9]{3}[-\s]?[0-9]{4,6}$/;
        return phoneRegex.test(phone.replace(/\s/g, ""));
    }

    /**
     * Afficher une erreur de champ
     */
    function showFieldError(fieldName, message) {
        const errorEl = document.getElementById(`error-${fieldName}`);
        const inputEl = document.getElementById(fieldName);
        
        if (errorEl) {
            errorEl.textContent = message;
        }
        
        if (inputEl) {
            inputEl.classList.add("error");
            inputEl.classList.remove("valid");
        }
    }

    /**
     * Effacer une erreur de champ
     */
    function clearFieldError(fieldName) {
        const errorEl = document.getElementById(`error-${fieldName}`);
        const inputEl = document.getElementById(fieldName);
        
        if (errorEl) {
            errorEl.textContent = "";
        }
        
        if (inputEl) {
            inputEl.classList.remove("error");
            inputEl.classList.add("valid");
        }
    }

    /**
     * Valider en temps réel
     */
    document.querySelectorAll(".form-input").forEach(input => {
        input.addEventListener("blur", () => {
            if (input.value.trim()) {
                clearFieldError(input.id);
            }
        });
    });
});
