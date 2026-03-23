document.addEventListener("DOMContentLoaded", () => {
    const Menu = document.getElementById("MenuPannel");
    const Notif = document.getElementById("NotificationPannel");

    window.toggleMenu = function () {
        if (Menu.hidden) {
            Menu.hidden = false;
            Notif.hidden = true;
        } else {
            Menu.hidden = true;
        }
    };

    window.toggleNotif = function () {
        if (Notif.hidden) {
            Notif.hidden = false;
            Menu.hidden = true; 
        } else {
            Notif.hidden = true;
        }
    };
});