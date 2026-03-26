

const loginBtn = document.getElementById("login");
if (loginBtn) {
    loginBtn.onclick = function () {
        window.location.href = "/login";
    };
}

const guestBtn = document.getElementById("guest");
if (guestBtn) {
    guestBtn.onclick = function () {
        window.location.href = "/";
    };
}

document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="account_type"]');
    const labelGroupe = document.getElementById('labelGroupe');
    const inputGroupe = document.getElementById('inputGroupe');

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'pilote') {
                labelGroupe.textContent = 'École';
                inputGroupe.placeholder = 'École';
            } else if (this.value === 'recruteur') {
                labelGroupe.textContent = 'Entreprise';
                inputGroupe.placeholder = 'Entreprise';
            }
        });
    });
});