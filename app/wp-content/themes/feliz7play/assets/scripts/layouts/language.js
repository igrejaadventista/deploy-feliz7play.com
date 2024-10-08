document.addEventListener('DOMContentLoaded', () => {
    const url = window.location.pathname;
    const idioma = url.split('/')[1];
    const menu = document.querySelector('ul#' + idioma.toUpperCase());
    const menuConfig = document.querySelector('#user-' + idioma.toUpperCase());
    
    if (menu) {
        menu.classList.add('active');
    }

    if (menuConfig) {
        menuConfig.classList.add('active');
    }
});