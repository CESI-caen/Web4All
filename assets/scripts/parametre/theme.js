const toogleButton = document.querySelector('#ThemePage');
const body = document.body;

const savedTheme = localStorage.theme;

if (savedTheme) {
    body.classList.add(savedTheme);
}

toogleButton.addEventListener('click', () => {
    body.classList.toggle("dark");
    const theme = body.classList.contains("dark") ? "dark" : "";
    localStorage.theme = theme;
});


window.matchMedia("prefers-color-scheme: dark").addEventListener
("change", (event) => {
    body.classList.remove("dark");
    localStorage.theme = "";
    if (event.matches) {
        body.classList.add("dark");
        localStorage.theme = "dark";
    }
});

