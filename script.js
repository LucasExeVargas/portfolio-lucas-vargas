document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('#checkbox');
    const body = document.body;
    const textElements = document.querySelectorAll('.text-nav');
    const img = document.querySelector('.img-yo');
    const carousel = document.getElementById('carouselExampleCaptions');
    const btnMy = document.querySelectorAll('.custom-animated-btn');

    // Activar tema claro al inicio
    body.classList.add('light-theme');
    img.src = 'img/yo-dia.svg';
    carousel.className = 'carousel carousel-dark slide';
    textElements.forEach(el => {
        el.classList.add('text-nav-light');
        el.classList.remove('text-nav-dark');
    });
    btnMy.forEach(btn => {
        btn.classList.add('custom-animated-btn-light');
        btn.classList.remove('custom-animated-btn-dark');
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
                carousel.className = 'carousel carousel-dark slide';
                textElements.forEach(el => {
                    el.classList.remove('text-nav-dark');
                    el.classList.add('text-nav-light');
                });
                btnMy.forEach(btn => {
                    btn.classList.add('custom-animated-btn-light');
                    btn.classList.remove('custom-animated-btn-dark');
                });
            } else {
                body.classList.remove('light-theme');
                body.classList.add('dark-theme');
                img.src = 'img/yo-noche.svg';
                carousel.className = 'carousel slide';
                textElements.forEach(el => {
                    el.classList.remove('text-nav-light');
                    el.classList.add('text-nav-dark');
                });
                btnMy.forEach(btn => {
                    btn.classList.add('custom-animated-btn-dark');
                    btn.classList.remove('custom-animated-btn-light');
                });
            }
        });
    });
});
