document.addEventListener('DOMContentLoaded', () => {
    const $header = document.getElementById('header'),
          $buttonLanguage = document.getElementById('button-language'),
          $buttonUser = document.getElementById('button-user'),
          $buttonMenuMobile = document.getElementById('button-menu-mobile');

    $buttonLanguage.addEventListener('click', () => $buttonLanguage.classList.toggle('active'));
    $buttonMenuMobile.addEventListener('click', () => $buttonMenuMobile.classList.toggle('active'));

    if($buttonUser)
        $buttonUser.addEventListener('click', () => $buttonUser.classList.toggle('active'));

    document.addEventListener('click', (event) => {
        if(!event.target.closest('#button-language'))
            $buttonLanguage.classList.remove('active');
        if(!event.target.closest('#button-user') && $buttonUser)
            $buttonUser.classList.remove('active');
        if(!event.target.closest('#button-menu-mobile'))
            $buttonMenuMobile.classList.remove('active');
    }, false);

    window.addEventListener('scroll', () => $header.classList.toggle('black', window.scrollY > 100));
});