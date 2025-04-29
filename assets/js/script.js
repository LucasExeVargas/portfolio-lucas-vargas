document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('#checkbox');
    const body = document.body;
    const textElements = document.querySelectorAll('.text-nav');
    const img = document.querySelector('.img-yo');
    const carousel = document.getElementById('carouselExampleCaptions');
    const btnMy = document.querySelectorAll('.custom-animated-btn');
    const btnCV = document.querySelector('.btn-cv'); // Botón para descargar CV
    const cards = document.querySelectorAll('.card');
    const heads = document.querySelectorAll('.head');
    const contents = document.querySelectorAll('.content');
    const socialIcons = document.querySelectorAll('.bi'); // Selecciona todos los iconos de las redes sociales

    // Activar tema claro al inicio
    body.classList.add('light-theme');
    img.src = '/assets/img/yo-dia.svg';
    carousel.className = 'carousel carousel-dark slide';
    textElements.forEach(el => {
        el.classList.add('text-nav-light');
        el.classList.remove('text-nav-dark');
    });
    btnMy.forEach(btn => {
        btn.classList.add('custom-animated-btn-light');
        btn.classList.remove('custom-animated-btn-dark');
    });
    cards.forEach(card => {
        card.classList.add('card-light');
        card.classList.remove('card-dark');
    });
    heads.forEach(head => {
        head.classList.add('head-light');
        head.classList.remove('head-dark');
    });
    contents.forEach(content => {
        content.classList.add('content-light');
        content.classList.remove('content-dark');
    });

    // Cambiar los íconos de redes sociales a color oscuro por defecto
    socialIcons.forEach(icon => {
        icon.classList.add('text-dark');
        icon.classList.remove('text-white');
    });

    checkboxes.forEach(checkbox => {
        checkbox.checked = true;

        checkbox.addEventListener('change', () => {
            const isLight = checkbox.checked;

            checkboxes.forEach(cb => cb.checked = isLight); // Sincroniza ambos switches

            if (isLight) {
                body.classList.remove('dark-theme');
                body.classList.add('light-theme');
                img.src = '/assets/img/yo-dia.svg';
                carousel.className = 'carousel carousel-dark slide';
                textElements.forEach(el => {
                    el.classList.remove('text-nav-dark');
                    el.classList.add('text-nav-light');
                });
                btnMy.forEach(btn => {
                    btn.classList.add('custom-animated-btn-light');
                    btn.classList.remove('custom-animated-btn-dark');
                });
                cards.forEach(card => {
                    card.classList.add('card-light');
                    card.classList.remove('card-dark');
                });
                heads.forEach(head => {
                    head.classList.add('head-light');
                    head.classList.remove('head-dark');
                });
                contents.forEach(content => {
                    content.classList.add('content-light');
                    content.classList.remove('content-dark');
                });

                // Cambiar los íconos de redes sociales a color oscuro
                socialIcons.forEach(icon => {
                    icon.classList.add('text-dark');
                    icon.classList.remove('text-white');
                });
            } else {
                body.classList.remove('light-theme');
                body.classList.add('dark-theme');
                img.src = '/assets/img/yo-noche.svg';
                carousel.className = 'carousel slide';
                textElements.forEach(el => {
                    el.classList.remove('text-nav-light');
                    el.classList.add('text-nav-dark');
                });
                btnMy.forEach(btn => {
                    btn.classList.add('custom-animated-btn-dark');
                    btn.classList.remove('custom-animated-btn-light');
                });
                cards.forEach(card => {
                    card.classList.remove('card-light');
                    card.classList.add('card-dark');
                });
                heads.forEach(head => {
                    head.classList.remove('head-light');
                    head.classList.add('head-dark');
                });
                contents.forEach(content => {
                    content.classList.remove('content-light');
                    content.classList.add('content-dark');
                });

                // Cambiar los íconos de redes sociales a color blanco
                socialIcons.forEach(icon => {
                    icon.classList.add('text-white');
                    icon.classList.remove('text-dark');
                });
            }
        });
    });
});
