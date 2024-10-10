document.addEventListener('DOMContentLoaded', () => {
    const idioma = getLanguage(),
          menu = document.querySelector('ul#' + idioma),
          menuConfig = document.querySelector('#user-' + idioma),
          Genre = document.getElementById('genre-' + idioma);

    activeClass(menu);
    activeClass(menuConfig);
    activeClass(Genre);
});

export function getLanguage() {
    const url = window.location.pathname,
          idioma = url.split('/')[1];

    return idioma.toUpperCase();
    // return 'ES';
}

function activeClass (className) {
    if (className) {
        className.classList.add('active');
    }
}