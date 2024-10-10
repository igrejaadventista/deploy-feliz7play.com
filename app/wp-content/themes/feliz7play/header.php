<?php 
    $menu = get_field('header', 'site_settings');
    $feedback = get_field('link_feedback', 'site_settings');
    $privacy_policy = get_field('link_privacy_policy', 'site_settings');
    $lang = getLanguage();
    $user = getUser();
    $mainMenu = get_field('languages', 'main_menu');     
    $termsLanguage = getTermsByLanguage('genre');
    $activeImageLang = getActiveImage($lang);       
?>

<header id="header" class="header">
    <div class="container-fluid">
        <div class="left">
            <a href="<?= network_site_url($lang) ?>">
                <svg width="186" height="36" viewBox="0 0 186 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M95.5478 0C85.6852 0 77.6489 8.03624 77.6489 17.8989C77.6489 27.7616 85.6852 35.7978 95.5478 35.7978C105.41 35.7978 113.447 27.7616 113.447 17.8989C113.447 8.03624 105.41 0 95.5478 0ZM106.35 11.8978V11.95C106.35 11.95 106.35 11.95 106.35 12.0022V12.0544C106.35 12.0544 106.35 12.0544 106.35 12.1065V12.1587V12.2109V12.2631V12.3153V12.3675V12.4196C106.35 12.4196 106.35 12.4196 106.35 12.4718V12.524V12.5762C106.35 12.5762 106.35 12.6284 106.298 12.6284C106.298 12.6284 106.298 12.6806 106.245 12.6806C106.245 12.6806 106.245 12.7327 106.193 12.7327L97.2699 28.179C96.9046 28.753 96.2784 29.1183 95.6522 29.1183C95.3391 29.1183 95.026 29.014 94.7129 28.8574C93.8258 28.3356 93.5127 27.1875 94.0345 26.3004L101.34 13.6199H89.8598L91.5297 16.4899C92.0515 17.3771 91.7906 18.5251 90.9035 19.0469C90.5904 19.2035 90.2773 19.3078 89.9642 19.3078C89.338 19.3078 88.7118 18.9947 88.3465 18.4207L85.059 12.8371C84.5371 11.95 84.798 10.802 85.6852 10.2801C85.7373 10.2279 85.7895 10.2279 85.8939 10.1758C86.1548 10.0714 86.4157 9.96702 86.6767 9.96702H104.471C104.628 9.96702 104.732 9.96702 104.889 10.0192C104.941 10.0192 104.941 10.0192 104.993 10.0192H105.045H105.097C105.097 10.0192 105.097 10.0192 105.15 10.0192H105.202H105.254C105.254 10.0192 105.254 10.0192 105.306 10.0192C105.306 10.0192 105.358 10.0192 105.358 10.0714C105.358 10.0714 105.41 10.0714 105.41 10.1236L105.463 10.1758C105.463 10.1758 105.515 10.1758 105.515 10.2279C105.515 10.2279 105.515 10.2279 105.567 10.2279C105.567 10.2279 105.567 10.2279 105.619 10.2801C105.619 10.2801 105.671 10.2801 105.671 10.3323L105.724 10.3845C105.828 10.4889 105.88 10.541 105.932 10.6454L105.984 10.6976L106.037 10.7498C106.193 10.9585 106.245 11.2194 106.298 11.4803V11.5325C106.298 11.5325 106.298 11.5325 106.298 11.5847V11.6369C106.35 11.8456 106.35 11.8456 106.35 11.8978C106.35 11.8456 106.35 11.8456 106.35 11.8978Z" fill="url(#paint0_linear)"/>
                    <path d="M3.60065 13.6198V18.0032H10.6454C11.6369 18.0032 12.6284 18.9947 12.6284 19.934C12.6284 20.8211 11.6369 21.6039 10.6454 21.6039H3.60065V27.3962C3.60065 28.3355 2.92227 29.1183 1.93078 29.1183C0.730567 29.1183 0 28.3877 0 27.3962V11.7412C0 10.7497 0.730567 10.0192 1.93078 10.0192H11.5847C12.7849 10.0192 13.5155 10.7497 13.5155 11.7412C13.5155 12.6283 12.7849 13.6198 11.5847 13.6198H3.60065Z" fill="#F16723"/>
                    <path d="M18.2643 29.0661C17.325 29.0661 16.5422 28.3355 16.5422 27.344V11.689C16.5422 10.6975 17.2728 9.96698 18.2643 9.96698H28.1791C30.7361 9.96698 30.6317 13.5676 28.1791 13.5676H20.2473V17.7423H27.1876C29.6403 17.7423 29.6403 21.343 27.1876 21.343H20.2473V25.4654H28.6488C31.1536 25.4654 31.3623 29.0661 28.6488 29.0661H18.2643Z" fill="#F16723"/>
                    <path d="M33.5017 11.689C33.5017 10.8019 34.2845 10.0192 35.2238 10.0192C36.1109 10.0192 36.8414 10.8019 36.8414 11.689V25.4654H44.3559C47.0694 25.4654 47.1216 29.0661 44.3559 29.0661H35.2759C34.3366 29.0661 33.5539 28.3877 33.5539 27.3962V11.689H33.5017Z" fill="#F16723"/>
                    <path d="M49.4177 11.6891C49.4177 9.44519 53.0706 9.34082 53.0706 11.6891V27.3963C53.0706 29.6923 49.4177 29.7445 49.4177 27.3963V11.6891Z" fill="#F16723"/>
                    <path d="M57.5061 29.0661C55.784 29.0661 55.1057 27.3962 56.0971 26.196L65.9076 13.1502H57.767C55.3666 13.1502 55.5753 9.75827 57.767 9.75827H68.8821C70.9694 9.75827 71.5956 11.7412 70.0823 13.3589L60.6893 25.5698H69.6126C71.8043 25.5698 72.0653 29.0661 69.4039 29.0661H57.5061Z" fill="#F16723"/>
                    <path d="M120.074 11.7934C120.074 10.9063 120.804 9.91479 121.953 9.91479H128.319C131.92 9.91479 135.103 12.3152 135.103 16.9074C135.103 21.2386 131.867 23.6912 128.319 23.6912H123.675V27.344C123.675 28.5443 122.892 29.2226 121.9 29.2226C121.013 29.2226 120.022 28.5443 120.022 27.344V11.7934H120.074ZM123.675 13.3589V20.1949H128.267C130.093 20.1949 131.554 18.5772 131.554 16.8552C131.554 14.9244 130.093 13.3067 128.267 13.3067H123.675V13.3589Z" fill="#F16723"/>
                    <path d="M138.025 11.7934C138.025 10.9063 138.808 10.1235 139.747 10.1235C140.634 10.1235 141.365 10.9063 141.365 11.7934V25.5698H148.827C151.541 25.5698 151.593 29.1705 148.827 29.1705H139.695C138.756 29.1705 137.973 28.4921 137.973 27.5006V11.7934H138.025Z" fill="#F16723"/>
                    <path d="M154.045 29.1705C153.158 28.6486 152.636 27.7093 153.158 26.5091L161.038 11.0628C161.768 9.6017 163.751 9.54951 164.43 11.0628L172.205 26.5613C173.353 28.7008 170.013 30.4229 169.022 28.2833L167.822 25.8829H157.646L156.446 28.2833C156.028 29.1705 155.037 29.3792 154.045 29.1705ZM165.891 22.1779L162.76 15.3941L159.368 22.1779H165.891Z" fill="#F16723"/>
                    <path d="M169.805 12.7849C168.5 11.0107 171.474 8.81898 172.883 10.9585L177.684 18.1598L182.537 10.9585C183.998 8.92334 187.077 10.9585 185.616 12.7849L179.406 21.7605V27.4484C179.406 29.8489 175.753 29.7967 175.753 27.3963V21.8126L169.805 12.7849Z" fill="#F16723"/>
                    <defs>
                    <linearGradient id="paint0_linear" x1="95.5649" y1="35.7571" x2="95.5649" y2="0" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#F58220"/>
                    <stop offset="1" stop-color="#F05125"/>
                    </linearGradient>
                    </defs>
                </svg>
            </a>

            
            <?php if(!empty($mainMenu)):?>
                <?php foreach($mainMenu as $languageMenu): ?>
                    <ul class="" id="<?= strtoupper($languageMenu['language']) ?>">
                        <?php if(!empty($languageMenu['home'])):?>
                            <li>
                                <a href="<?= network_site_url($lang) ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                                        <path d="M14 6.688V14c0 .898-.692 1.625-1.546 1.625H4.625a2 2 0 0 1-2-2V6.688M1 6.688l6.255-5.301a1.639 1.639 0 0 1 2.115 0l6.255 5.3" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M10.75 14.813V10.62c0-.826-.727-1.496-1.625-1.496H7.5c-.897 0-1.625.67-1.625 1.496v4.191" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </a>
                            </li>                           
                        <?php endif; ?>

                        <?php foreach($languageMenu['links'] as $menuItem): ?>
                            <li>
                                <a href="<?= $menuItem['links']['url'] ?>" target="<?= !empty($menuItem['links']['target']) ? $menuItem['links']['target'] : '_self' ?>">
                                        <span><?= $menuItem['links']['title'] ?></span>
                                    </a>
                                </li>
                        <?php endforeach; ?>     
                    </ul>
                    <div class="header__genre">
                        <spam href="" class="dropdownGenreAnchor" id="genre-<?= strtoupper($languageMenu['language']) ?>">
                            <?= $languageMenu['gender_titile'] ?>
                            <svg width="11" height="8" viewBox="0 0 11 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.834 0h-8a1 1 0 0 0-.8 1.6l4 5.333a1 1 0 0 0 1.6 0l4-5.333a1 1 0 0 0-.8-1.6z" fill="#F16723"></path>
                            </svg>
                        </spam>

                        <div class="dropdownGenre" data-genre="<?= strtoupper($languageMenu['language']) ?>">
                            <?php foreach($termsLanguage[strtoupper($languageMenu['language'])] as $term): ?> 
                                <button>
                                    <a href="<?= network_site_url($lang . '/g/' . $term['slug']) ?>">
                                        <?= $term['title'] ?>
                                    </a>
                                </button>    
                            <?php endforeach; ?>            
                        </div>
                    </div>                   
                <?php endforeach; ?> 
            <?php endif; ?>
        </div> 

        <div class="right">
            <a class="search" href="<?= network_site_url($lang . '/busca') ?>">
                <svg width="14.7" height="14.7" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M19.7675 11.467V11.467C19.7675 15.591 16.4245 18.934 12.3005 18.934V18.934C8.1765 18.934 4.8335 15.591 4.8335 11.467V11.467C4.8335 7.343 8.1765 4 12.3005 4V4C16.4245 4 19.7675 7.343 19.7675 11.467Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M20.8335 20L17.5835 16.75" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>

            <div class="header__language">
                <button id="button-language" class="selected">
                    <img src = "<?= $activeImageLang ?>" alt="<?= $lang ?>"/>
                    <span><?= $lang ?></span>
                    
                    <div>
                        <svg width="11" height="8" viewBox="0 0 11 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.834 0h-8a1 1 0 0 0-.8 1.6l4 5.333a1 1 0 0 0 1.6 0l4-5.333a1 1 0 0 0-.8-1.6z" fill="#F16723"></path>
                        </svg>
                    </div>
                </button>

                <div class="dropdownLang">
                    <?php if(!empty($mainMenu)):?>
                        <?php foreach($mainMenu as $languageMenu): ?>
                            <button>
                                <div>
                                    <a href="<?= network_site_url(strtolower($languageMenu['language'])) ?>">
                                        <img src="<?= $languageMenu['language_image']['url'] ?>" alt="<?= $languageMenu['language_image']['alt'] ?>">
                                        
                                        <span><?= strtoupper($languageMenu['language']) ?></span>
                                    </a>
                                </div>
                            </button>
                        <?php endforeach; ?> 
                    <?php endif; ?>
                </div>
            </div>

            <div class="header__user">
                <?php if(empty($user['name'])): ?>
                    <a href="<?= network_site_url('/login') ?>" class="header-not-login">
                        <span class="my-account">Minha Conta</span>
                        
                        <span class="my-avatar">
                            <svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.026 21.568c1.74-10.093 16.234-10.093 17.974 0" stroke="#fff" stroke-width="2"></path>
                                
                                <circle cx="10.013" cy="5.392" r="4.392" stroke="#fff" stroke-width="2"></circle>
                            </svg>
                        </span>
                    </a>
                <?php else: ?>
                    <?php foreach($mainMenu as $languageMenu): ?>
                        <a href="<?= $languageMenu['login_config']['url']; ?>" id="user-<?= strtoupper($languageMenu['language']) ?>" class="user">
                            <div class="avatar">
                                <img src="<?= $user['avatar'] ?>">
                            </div>
                            <span>
                                <?= $user['name'] ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                    <a href="<?= wp_logout_url(network_site_url('/login')) ?>" class="signout_icon">
                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_2684_6131)">
                                <path d="M11.375 4.375H5.25C5.01794 4.375 4.79538 4.46719 4.63128 4.63128C4.46719 4.79538 4.375 5.01794 4.375 5.25V22.75C4.375 22.9821 4.46719 23.2046 4.63128 23.3687C4.79538 23.5328 5.01794 23.625 5.25 23.625H11.375" stroke="#F16723" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M11.375 14H23.625" stroke="#F16723" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M19.25 9.625L23.625 14L19.25 18.375" stroke="#F16723" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                            <defs>
                                <clipPath id="clip0_2684_6131">
                                    <rect width="28" height="28" fill="white"></rect>
                                </clipPath>
                            </defs>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>