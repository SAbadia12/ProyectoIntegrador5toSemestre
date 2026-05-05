const menuToggle = document.getElementById('menu-toggle');
const slidebar = document.getElementById('slidebar');

if (menuToggle && slidebar) {
    menuToggle.addEventListener('click', () => {
        slidebar.classList.toggle('slidebar-open');
    });
}

// Acordeón para Config Zonas
const accordionBtn = document.getElementById('zonas-accordion-btn');
const accordionContent = document.getElementById('zonas-accordion-content');

if (accordionBtn && accordionContent) {
    accordionBtn.addEventListener('click', () => {
        accordionBtn.classList.toggle('active');
        accordionContent.classList.toggle('active');
    });

    // Auto-expandir si alguno de los items está activo
    const activeItems = accordionContent.querySelectorAll('.accordion-item.active');
    if (activeItems.length > 0) {
        accordionBtn.classList.add('active');
        accordionContent.classList.add('active');
    }
}
