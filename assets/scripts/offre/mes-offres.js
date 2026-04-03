document.addEventListener('DOMContentLoaded', function () {
    const btn = document.querySelector('.btn-Create');
    const editionContainer = document.querySelector('.Edition-container');
    const offresContainer = document.querySelector('.Offres');

    btn.addEventListener('click', function () {
        editionContainer.classList.toggle('hidden');
        editionContainer.classList.toggle('active');
        offresContainer.classList.toggle('hidden');

        if (editionContainer.classList.contains('active')) {
            btn.textContent = 'Annuler';
        } else {
            btn.textContent = 'Créer une offre';
        }
    });

    const btnDelete = document.querySelectorAll('.Offre-btn-Delete');
    btnDelete.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            fetch('/offre/delete/' + id, {
                method: 'POST'
            })
            .then(() => {
                this.closest('.Offre').remove();
            });
        });
    });

    const btnEdit = document.querySelectorAll('.Offre-btn-Edit');
    btnEdit.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const btnCreate = document.querySelector('.btn-Create');
            const form = document.querySelector('.Edition-form');
            
            editionContainer.classList.toggle('hidden');
            editionContainer.classList.toggle('active');
            offresContainer.classList.toggle('hidden');

            if (editionContainer.classList.contains('active')) {
                btnCreate.textContent = 'Annuler';
                form.querySelector('input[name="offre-id"]').value = id;
                form.querySelector('input[name="offre-name"]').value = this.dataset.nom;
                form.querySelector('textarea[name="offre-description"]').value = this.dataset.description;
                form.querySelector('input[name="offre-start"]').value = this.dataset.start;
                form.querySelector('input[name="offre-end"]').value = this.dataset.end;
                form.querySelector('input[name="offre-salary"]').value = this.dataset.salary;
            } else {
                btnCreate.textContent = 'Créer une offre';
                form.querySelector('input[name="offre-id"]').value = '';
                form.querySelector('input[name="offre-name"]').value = '';
                form.querySelector('input[name="offre-description"]').value = '';
                form.querySelector('input[name="offre-start"]').value = '';
                form.querySelector('input[name="offre-end"]').value = '';
                form.querySelector('input[name="offre-salary"]').value = '';
            }
            });
    });
});