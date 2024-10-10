import { getLanguage } from './language';

document.addEventListener('DOMContentLoaded', () => {
    const $header = document.getElementById('header'),
          $buttonLanguage = document.getElementById('button-language'),
          $headerLanguage = document.querySelector('.header__language'),
          $buttonUser = document.getElementById('button-user'),
          $buttonMenuMobile = document.getElementById('button-menu-mobile'),
          $headerGenre = document.querySelector('.header__genre'),
          $dropdownGenreTarget = document.querySelector('[data-genre='+ getLanguage() +']');
   
    $headerLanguage.addEventListener('mouseenter', () => $headerLanguage.classList.add('active')); 
    $headerLanguage.addEventListener('mouseleave', () => $headerLanguage.classList.remove('active'));
    $buttonLanguage.addEventListener('click', () => $buttonMenuMobile.classList.toggle('active'));
    

    if (window.matchMedia('(max-width: 560px)').matches) {
        $headerGenre.addEventListener('click', () => $dropdownGenreTarget.classList.toggle('open'));
    } else {
        $headerGenre.addEventListener('mouseenter', () => $dropdownGenreTarget.classList.add('open'));    
        $headerGenre.addEventListener('mouseleave', () => $dropdownGenreTarget.classList.remove('open')); 
    }

    if($buttonUser)
        $buttonUser.addEventListener('click', () => $buttonUser.classList.toggle('active'));

    document.addEventListener('click', (event) => {
        if(!event.target.closest('#button-language'))
            $buttonLanguage.classList.remove('active');
        if(!event.target.closest('#button-user') && $buttonUser)
            $buttonUser.classList.remove('active');
    }, false);

    window.addEventListener('scroll', () => $header.classList.toggle('black', window.scrollY > 100)); 
});