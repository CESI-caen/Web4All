// ########################### FONCTIONNALITEES PRESENTES ############################
// - List 'dropdown' intéractive avec petit affichage des sélections sur le bouton   #
// concerné.                                                                         #
// - Masquage de certains filtres en fonction du 'type' de la recherche (offre,      #
// entreprise, colmpte).                                                             #
// ###################################################################################

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dropdown-checkbox').forEach(dropdown => { // Sélectionne tous les éléments avec la classe 'dropdown-checkbox' et itère dessus
        const trigger    = dropdown.querySelector('.dropdown-trigger'); // Sélectionne le bouton qui déclenche l'ouverture du dropdown
        const liste      = dropdown.querySelector('.dropdown-liste'); // Sélectionne la liste déroulante qui contient les villes
        const checkboxes = dropdown.querySelectorAll('input[type="checkbox"]'); // Sélectionne toutes les checkboxes (villes) à l'intérieur du dropdown

        // Mise à jour du compteur
        function mettreAJourCompteur() { // pour raffraichir le nombres de villes sélectionnées
            const coches = [...dropdown.querySelectorAll('input[type="checkbox"]:checked')].map(cb => cb.value); // Récupère toutes les checkboxes cochées et crée un tableau de leurs valeurs. map = tableau, cb = checkbox, value = 'value' en html (ici twig: {{ ville }})
            const MAX = 4; // Nimbre max de villes à afficher avant de faire '+ X'
            const noms = coches.slice(0, MAX).join(', '); // Prend les premières 'MAX' villes cochées et les met dans une chaîne de caractères séparée par des virgules
            const surplus = coches.length > MAX ? ` +${coches.length - MAX}` : ''; // Si le nombre de villes cochées dépasse 'MAX' alors affiche '+' avec le nombre de villes supplémentaires, sinon rien
            trigger.textContent = coches.length === 0 ? '0 sélectionné' : noms + surplus; // Applique le texte sur le bouton de déclenchement du dropdown en fonction du nombre de villes cochées
        }

        mettreAJourCompteur(); // Appelle la fonction pour initialiser le compteur
        checkboxes.forEach(cb => cb.addEventListener('change', mettreAJourCompteur)); // Ajoute un écouteur d'événement 'change' à chaque checkbox pour mettre à jour le compteur lors d'un cochage/décochage

        // Gestion ouverture / fermeture
        trigger.addEventListener('click', () => { // Ajoute un écouteur d'événement 'click' au bouton de déclenchement du dropdown pour ouvrir/fermer la liste déroulante
            const isOpen = !liste.hidden; // Vérifie si 'liste' est actuellement ouverte (!hidden), isOpen est un booléen
            liste.hidden = isOpen; // Si caché -> affiche (ouvre la lsite), si affiché -> cache (ferme la liste)
            trigger.setAttribute('aria-expanded', String(!isOpen)); // Applique l'attribut 'aria-expanded' pour indiquer si le dropdown est ouvert ou fermé (important pour l'accessibilité) 
        });

        document.addEventListener('click', e => { // Ajoute un écouteur d'événement 'click' au document pour fermer le dropdown si l'utilisateur clique en dehors de celui-ci
            if (!dropdown.contains(e.target)) { // Regarde où l'utilisateur 'click' (e;target) et vérifie si c'est à l'intérieur du dropdown, puis prend l'inverse (avec '!')
                liste.hidden = true; // Cache (ferme) la liste déroulante
                trigger.setAttribute('aria-expanded', 'false'); // Enlève l'attribut 'aria-expanded' (pour l'accessibilité) lorsque le dropdown est fermé
            }
        });
    });

    // --- Masquage des filtres selon le type de recherche ---
    const filtreType = document.querySelector('#filtre-type'); // id, pas name

    function appliquerFiltreType() {
        const dateContainer    = document.querySelector('#filtre-date-container');
        const villeContainer   = document.querySelector('#filtre-ville-container');
        const domaineContainer = document.querySelector('#filtre-domaine-container');
        const estComptes       = filtreType.value === 'comptes';

        dateContainer.style.display    = estComptes ? 'none' : '';
        villeContainer.style.display   = estComptes ? 'none' : '';
        domaineContainer.style.display = estComptes ? 'none' : '';
    }

    // TODO 2 : initialiser la visibilité dès le chargement
    appliquerFiltreType();
    filtreType.addEventListener('change', appliquerFiltreType);

});


// ####################################################################################
// TODO :
// - 