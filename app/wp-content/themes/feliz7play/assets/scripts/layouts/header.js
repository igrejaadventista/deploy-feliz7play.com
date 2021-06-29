document.addEventListener('DOMContentLoaded', () => {
    const $header = document.getElementById('header'),
          $buttonLanguage = document.getElementById('button-language'),
          $buttonUser = document.getElementById('button-user');

    $buttonLanguage.addEventListener('click', () => $buttonLanguage.classList.toggle('active'));
    $buttonUser.addEventListener('click', () => $buttonUser.classList.toggle('active'));

    document.addEventListener('click', (event) => {
        if(!event.target.closest('#button-language'))
            $buttonLanguage.classList.remove('active');
        if(!event.target.closest('#button-user'))
            $buttonUser.classList.remove('active');
    }, false);

    window.addEventListener('scroll', () => $header.classList.toggle('black', window.scrollY > 100));
});