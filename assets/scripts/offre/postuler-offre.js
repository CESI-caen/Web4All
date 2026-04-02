/**
 * JavaScript pour la page de candidature
 * Gère la soumission du formulaire de postulation
 */

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("form-candidature");
    const offreData = document.getElementById("offre-data");
    const offreId = offreData ? offreData.dataset.offreId : null;
    const formSection = document.querySelector(".postuler-form-section");
    const successMessage = document.getElementById("success-message");
    const btnSoumettre = document.getElementById("btn-soumettre");

    if (!form) {
        console.error("Formulaire de candidature introuvable");
        return;
    }

    // Initialiser le formulaire
    initializeForm();

    /**
     * Initialiser le formulaire
     */
    function initializeForm() {
        // Submission du formulaire
        form.addEventListener("submit", handleFormSubmit);
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
        document.querySelectorAll(".form-textarea").forEach(input => {
            input.classList.remove("error", "valid");
        });

        // Lettre de motivation
        const lettre = document.getElementById("lettre").value.trim();
        if (!lettre || lettre.length < 50) {
            showFieldError("lettre", "La lettre doit faire au moins 50 caractères");
            isValid = false;
        } else if (lettre.length > 5000) {
            showFieldError("lettre", "La lettre ne doit pas dépasser 5000 caractères");
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
     * Soumettre le formulaire au backend
     */
    function submitForm() {
        const submitBtn = document.querySelector(".form-actions .btn-primary") || btnSoumettre;
        const originalText = submitBtn ? submitBtn.textContent : "Soumettre ma candidature";

        // État de chargement
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add("btn-loading");
            submitBtn.textContent = "Envoi en cours...";
        }
        form.classList.add("form-loading");

        // Récupérer les données du formulaire
        const lettre = document.getElementById("lettre").value.trim();

        // Envoyer au backend
        fetch(`/offre/${offreId}/postuler/submit`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                lettre: lettre
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Succès - Afficher le message de succès
                form.style.display = "none";
                if (successMessage) {
                    successMessage.style.display = "block";
                    successMessage.scrollIntoView({ behavior: "smooth" });
                }
            } else {
                // Erreur
                showError(data.message || "Une erreur s'est produite");
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove("btn-loading");
                    submitBtn.textContent = originalText;
                }
                form.classList.remove("form-loading");
            }
        })
        .catch(error => {
            console.error("Erreur réseau:", error);
            showError("Erreur de connexion au serveur. Veuillez réessayer.");
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove("btn-loading");
                submitBtn.textContent = originalText;
            }
            form.classList.remove("form-loading");
        });
    }

    /**
     * Afficher une erreur globale
     */
    function showError(message) {
        const errorDiv = document.createElement("div");
        errorDiv.className = "error-message";
        errorDiv.style.cssText = `
            background-color: #ffebee;
            border: 1px solid #d32f2f;
            color: #d32f2f;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        `;
        errorDiv.textContent = message;

        formSection.insertBefore(errorDiv, form);

        // Auto-remove après 5 secondes
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
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
    const letreInput = document.getElementById("lettre");
    if (letreInput) {
        letreInput.addEventListener("input", (e) => {
            const length = e.target.value.length;
            const helper = letreInput.parentElement.querySelector(".form-helper");
            
            if (helper) {
                if (length < 50) {
                    helper.textContent = `Minimum 50 caractères (${length}/50)`;
                    helper.style.color = "#d32f2f";
                } else {
                    helper.textContent = `${length}/5000 caractères`;
                    helper.style.color = "#666";
                }
            }

            // Auto-clear error on input
            if (length > 0) {
                clearFieldError("lettre");
            }
        });
    }

    document.querySelectorAll(".form-input").forEach(input => {
        input.addEventListener("blur", () => {
            if (input.value.trim()) {
                clearFieldError(input.id);
            }
        });
    });
});
