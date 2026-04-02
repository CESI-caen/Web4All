

document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="account_type"]');
    const labelGroupe = document.getElementById('labelGroupe');
    const inputGroupe = document.getElementById('inputGroupe');

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            const entrepriseInfo = document.getElementById("entreprise-info");
            if (entrepriseInfo) {
                if (this.value === 'pilote') {
                    labelGroupe.textContent = 'École';
                    inputGroupe.placeholder = 'École';
                    inputGroupe.name = 'ecole';
                    entrepriseInfo.style.display = "none";
                } else if (this.value === 'recruteur') {
                    labelGroupe.textContent = 'Entreprise';
                    inputGroupe.placeholder = 'Entreprise';
                    inputGroupe.name = 'Entreprise';
                    entrepriseInfo.style.display = "block";
                }
            }
        });
    });

    const addDomainBtn = document.querySelector('button[id="add-domain"]');
    if (addDomainBtn) {
        addDomainBtn.addEventListener('click', ajouterDomaine);
    }
});

function ajouterDomaine() {
    const container = document.getElementById("domaines-container");

    const div = document.createElement("div");
    div.className = "domaine-item";

    const input = document.createElement("input");
    input.type = "text";
    input.name = "domaine[]";
    input.placeholder = "Domaine supplémentaire";

    const removeBtn = document.createElement("button");
    removeBtn.type = "button";
    removeBtn.textContent = "Supprimer";
    removeBtn.onclick = function() {
        container.removeChild(div);
    };

    div.appendChild(input);
    div.appendChild(removeBtn);
    container.appendChild(div);
}