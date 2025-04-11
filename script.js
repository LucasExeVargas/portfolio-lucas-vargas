document.addEventListener('DOMContentLoaded', () => {
    const checkbox = document.getElementById('checkbox');
    const body = document.body;
    const textElements = document.querySelectorAll('.text-nav');

    // Activar tema claro al inicio
    body.classList.add('light-theme');
    checkbox.checked = true;

    // Aplicar clases de texto según el tema inicial
    textElements.forEach(el => {
        el.classList.add('text-nav-light');
        el.classList.remove('text-nav-dark');
    });

    checkbox.addEventListener('change', () => {
        if (checkbox.checked) {
            body.classList.remove('dark-theme');
            body.classList.add('light-theme');

            textElements.forEach(el => {
                el.classList.remove('text-nav-dark');
                el.classList.add('text-nav-light');
            });
        } else {
            body.classList.remove('light-theme');
            body.classList.add('dark-theme');

            textElements.forEach(el => {
                el.classList.remove('text-nav-light');
                el.classList.add('text-nav-dark');
            });
        }
    });
});
