document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('#checkbox'); // Ahora selecciona ambos
    const body = document.body;
    const textElements = document.querySelectorAll('.text-nav');
    const img = document.querySelector('.img-yo');

    // Activar tema claro al inicio
    body.classList.add('light-theme');
    img.src = 'img/yo-dia.svg';
    textElements.forEach(el => {
        el.classList.add('text-nav-light');
        el.classList.remove('text-nav-dark');
    });

    checkboxes.forEach(checkbox => {
        checkbox.checked = true;

        checkbox.addEventListener('change', () => {
            const isLight = checkbox.checked;

            checkboxes.forEach(cb => cb.checked = isLight); // Sincroniza ambos switches

            if (isLight) {
                body.classList.remove('dark-theme');
                body.classList.add('light-theme');
                img.src = 'img/yo-dia.svg';
                textElements.forEach(el => {
                    el.classList.remove('text-nav-dark');
                    el.classList.add('text-nav-light');
                });
            } else {
                body.classList.remove('light-theme');
                body.classList.add('dark-theme');
                img.src = 'img/yo-noche.svg';
                textElements.forEach(el => {
                    el.classList.remove('text-nav-light');
                    el.classList.add('text-nav-dark');
                });
            }
        });
    });
});
