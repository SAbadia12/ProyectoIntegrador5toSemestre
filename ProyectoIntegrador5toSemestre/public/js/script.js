const menuToggle = document.getElementById('menu-toggle');
const slidebar = document.getElementById('slidebar');

if (menuToggle && slidebar) {
    menuToggle.addEventListener('click', () => {
        slidebar.classList.toggle('slidebar-open');
    });
}
