document.addEventListener("DOMContentLoaded", () => {
    const Menu = document.getElementById("MenuPannel");
    const Notif = document.getElementById("NotificationPannel");
    const pc_width = 1024; // seuil PC

    function updateMenuDisplay() {            // Permet l'affichage du menu et des notifications en fonction de la taille de l'écran
        if (window.innerWidth >= pc_width) {
            Menu.hidden = false;
            Notif.hidden = true;
        } else {
            Menu.hidden = true;
            Notif.hidden = true;
        }
    }

    // Vérifie au chargement
    updateMenuDisplay();
    // Vérifie au redimensionnement
    window.addEventListener("resize", updateMenuDisplay);

    window.toggleMenu = function () {       // ToggleMenu uniquement si écran inférieur à pc_width
        if (window.innerWidth < pc_width) {
            Menu.hidden = !Menu.hidden;
            if (!Menu.hidden) Notif.hidden = true;
        }
    };
   window.toggleNotif = function () {
    if (Notif.hidden) {
        Notif.hidden = false;
    } else {
        Notif.hidden = true;
    }
    // Ne cacher le menu que si on n'est pas sur PC
    if (window.innerWidth < 1024 && !Notif.hidden) {
        document.getElementById("MenuPannel").hidden = true;
    }
};
});