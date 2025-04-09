document.addEventListener('DOMContentLoaded', () => {
    const checkbox = document.getElementById('checkbox');
    const body = document.body;

    // Activar tema claro al inicio
    body.classList.add('light-theme');
    checkbox.checked = true;

    checkbox.addEventListener('change', () => {
        if (checkbox.checked) {
            body.classList.remove('dark-theme');
            body.classList.add('light-theme');
            
        } else {
            
            body.classList.remove('light-theme');
            body.classList.add('dark-theme');
        }
    });
});
