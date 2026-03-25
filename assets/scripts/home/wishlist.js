document.querySelectorAll('.plus').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.classList.toggle('active');
    });
});

document.querySelectorAll('.heart').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.classList.toggle('active');
    });
});