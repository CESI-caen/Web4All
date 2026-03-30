const body = document.body;

const switchbox = document.querySelector(".switch");
const btn = document.querySelector(".btn");

const savedTheme = localStorage.theme;
console.log(savedTheme);

if (savedTheme) {
    body.classList.add(savedTheme);
}

if (switchbox) {
        switchbox.addEventListener("click", () => {
        btn.classList.toggle("btn-change");
        body.classList.toggle("dark");
        const theme = body.classList.contains("dark") ? "dark" : "";
        localStorage.theme = theme;
    });
}


window.matchMedia("prefers-color-scheme: dark").addEventListener
("change", (event) => {
    body.classList.remove("dark");
    localStorage.theme = "";
    if (event.matches) {
        body.classList.add("dark");
        localStorage.theme = "dark";
    }
});

