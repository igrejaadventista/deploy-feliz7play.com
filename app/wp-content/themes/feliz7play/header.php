<?php 
    $menu = get_field('header', 'site_settings');
    $feedback = get_field('link_feedback', 'site_settings');
    $privacy_policy = get_field('link_privacy_policy', 'site_settings');
    $lang = getLanguage();
    $user = getUser();
?>

<header id="header" class="header">
    <div class="container-fluid">
        <div class="left">
            <a href="<?= network_site_url($lang) ?>">
                <svg width="186" height="36" viewBox="0 0 186 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M95.548 0c-9.863 0-17.9 8.036-17.9 17.899 0 9.863 8.037 17.899 17.9 17.899 9.862 0 17.899-8.036 17.899-17.9C113.447 8.037 105.41 0 95.547 0zm10.802 11.898V12.576s0 .052-.052.052c0 0 0 .053-.053.053 0 0 0 .052-.052.052L97.27 28.179c-.365.574-.992.94-1.618.94-.313 0-.626-.105-.94-.262-.886-.521-1.2-1.67-.678-2.557l7.306-12.68H89.86l1.67 2.87c.522.887.26 2.035-.627 2.557-.313.156-.626.26-.939.26a1.922 1.922 0 0 1-1.617-.886l-3.288-5.584c-.522-.887-.261-2.035.626-2.557.052-.052.105-.052.209-.104.26-.105.522-.209.783-.209h17.794c.157 0 .261 0 .418.052h.417s.052 0 .052.052c0 0 .052 0 .052.053l.053.052s.052 0 .052.052h.052l.052.052s.052 0 .052.052l.053.052c.104.105.156.157.208.261l.052.053.053.052c.156.209.208.47.261.73v.157c.052.209.052.209.052.26 0-.051 0-.051 0 0z" fill="url(#a)"></path>

                    <path d="M3.6 13.62v4.383h7.045c.992 0 1.983.992 1.983 1.931 0 .887-.991 1.67-1.983 1.67H3.601v5.792c0 .94-.679 1.722-1.67 1.722-1.2 0-1.931-.73-1.931-1.722V11.741c0-.991.73-1.722 1.93-1.722h9.655c1.2 0 1.93.73 1.93 1.722 0 .887-.73 1.879-1.93 1.879H3.6zM18.264 29.066c-.939 0-1.722-.73-1.722-1.722V11.689c0-.992.73-1.722 1.722-1.722h9.915c2.557 0 2.453 3.6 0 3.6h-7.932v4.175h6.94c2.453 0 2.453 3.601 0 3.601h-6.94v4.122h8.402c2.505 0 2.713 3.601 0 3.601H18.264zM33.502 11.689c0-.887.782-1.67 1.722-1.67.887 0 1.617.783 1.617 1.67v13.776h7.515c2.713 0 2.766 3.601 0 3.601h-9.08c-.94 0-1.722-.678-1.722-1.67V11.69h-.052zM49.418 11.69c0-2.245 3.653-2.35 3.653 0v15.706c0 2.296-3.653 2.348-3.653 0V11.69zM57.506 29.066c-1.722 0-2.4-1.67-1.409-2.87l9.81-13.046h-8.14c-2.4 0-2.192-3.392 0-3.392h11.115c2.087 0 2.714 1.983 1.2 3.6L60.69 25.57h8.924c2.191 0 2.452 3.496-.21 3.496H57.507zM120.074 11.793c0-.887.73-1.878 1.879-1.878h6.366c3.601 0 6.784 2.4 6.784 6.992 0 4.332-3.236 6.784-6.784 6.784h-4.644v3.653c0 1.2-.783 1.879-1.775 1.879-.887 0-1.878-.679-1.878-1.879v-15.55h.052zm3.601 1.566v6.836h4.592c1.826 0 3.287-1.618 3.287-3.34 0-1.93-1.461-3.548-3.287-3.548h-4.592v.052zM138.025 11.793c0-.887.783-1.67 1.722-1.67.887 0 1.618.783 1.618 1.67V25.57h7.462c2.714 0 2.766 3.6 0 3.6h-9.132c-.939 0-1.722-.678-1.722-1.67V11.794h.052zM154.045 29.17c-.887-.521-1.409-1.46-.887-2.66l7.88-15.447c.73-1.461 2.713-1.513 3.392 0l7.775 15.498c1.148 2.14-2.192 3.862-3.183 1.722l-1.2-2.4h-10.176l-1.2 2.4c-.418.887-1.409 1.096-2.401.887zm11.846-6.992l-3.131-6.784-3.392 6.784h6.523zM169.805 12.785c-1.305-1.774 1.669-3.966 3.078-1.826l4.801 7.2 4.853-7.2c1.461-2.036 4.54 0 3.079 1.826l-6.21 8.976v5.687c0 2.4-3.653 2.349-3.653-.052v-5.583l-5.948-9.028z" fill="#F16723"></path>

                    <defs>
                        <linearGradient id="a" x1="95.565" y1="35.757" x2="95.565" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#F58220"></stop>

                            <stop offset="1" stop-color="#F05125"></stop>
                        </linearGradient>
                    </defs>
                </svg>
            </a>

            <?php if(!empty($menu)): ?>
                <ul>
                    <?php foreach($menu as $item): ?>
                        <li>
                            <a href="<?= $item['link']['url'] ?>" target="<?= !empty($item['link']['target']) ? $item['link']['target'] : '_self' ?>">
                                <?php if(!empty($menu)): ?>
                                    <?= file_get_contents($item['icon']) ?>
                                <?php endif; ?>

                                <span><?= $item['link']['title'] ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?> 
                </ul>
            <?php endif; ?>
        </div>
        
        <button id="button-menu-mobile" class="header__button-mobile">
            <i class="menu-icon"></i>
        </button>

        <aside class="header__menu-mobile">
            <?php if(!empty($menu)): ?>
                <ul>
                    <?php foreach($menu as $item): ?>
                        <li>
                            <a href="<?= $item['link']['url'] ?>" target="<?= !empty($item['link']['target']) ? $item['link']['target'] : '_self' ?>">
                                <?php if(!empty($menu)): ?>
                                    <div class="icon">
                                        <?= file_get_contents($item['icon']) ?>
                                    </div>
                                <?php endif; ?>

                                <span><?= $item['link']['title'] ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?> 

                    <li class="">
                        <a href="<?= network_site_url($lang . '/busca') ?>">
                            <div class="icon">
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path clip-rule="evenodd" d="M19.767 11.467a7.467 7.467 0 1 1-14.934 0 7.467 7.467 0 1 1 14.934 0z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    
                                    <path d="M20.834 20l-3.25-3.25" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            
                            <span>Pesquisar</span>
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
        </aside>
        
        <div class="right">
            <div class="header__language">
                <button id="button-language" class="selected">
                    <span><?= $lang ?></span>
                    
                    <div>
                        <svg width="11" height="8" viewBox="0 0 11 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.834 0h-8a1 1 0 0 0-.8 1.6l4 5.333a1 1 0 0 0 1.6 0l4-5.333a1 1 0 0 0-.8-1.6z" fill="#F16723"></path>
                        </svg>
                    </div>
                </button>

                <div class="dropdownLang">
                    <button>
                        <div>
                            <a href="<?= network_site_url('pt') ?>">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 24c6.627 0 12-5.373 12-12S18.627 0 12 0 0 5.373 0 12s5.373 12 12 12z" fill="#6DA544"></path>
                                    
                                    <path d="M12 4.696L21.913 12 12 19.305 2.087 12 12 4.696z" fill="#FFDA44"></path>
                                    
                                    <path d="M12 16.174a4.174 4.174 0 1 0 0-8.348 4.174 4.174 0 0 0 0 8.348z" fill="#F0F0F0"></path>
                                    
                                    <path d="M9.913 11.739a7.02 7.02 0 0 0-2.086.315 4.173 4.173 0 0 0 7.59 2.34 7.034 7.034 0 0 0-5.504-2.655zM16.096 12.8a4.174 4.174 0 0 0-7.932-2.447 8.607 8.607 0 0 1 7.932 2.447z" fill="#0052B4"></path>
                                </svg>
                                
                                <span>PT</span>
                            </a>
                        </div>
                    </button>
                    
                    <button>
                        <div>
                            <a href="<?= network_site_url('es') ?>">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 12c0 1.468.264 2.874.746 4.174L12 17.218l11.254-1.044c.482-1.3.746-2.706.746-4.174 0-1.467-.264-2.874-.746-4.174L12 6.783.746 7.826A11.974 11.974 0 0 0 0 12z" fill="#FFDA44"></path>
                                    
                                    <path d="M23.254 7.826C21.558 3.256 17.159 0 12 0 6.84 0 2.442 3.257.746 7.826h22.508zM.746 16.174C2.442 20.744 6.84 24 12 24c5.16 0 9.558-3.256 11.254-7.826H.746z" fill="#D80027"></path>
                                </svg>
                                
                                <span>ES</span>
                            </a>
                        </div>
                    </button>
                </div>
            </div>
            
            <a class="search" href="<?= network_site_url($lang . '/busca') ?>">
                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" d="M19.767 11.467a7.467 7.467 0 1 1-14.934 0 7.467 7.467 0 1 1 14.934 0z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    
                    <path d="M20.834 20l-3.25-3.25" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </a>
            
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
                    <button id="button-user" class="user">
                        <div class="avatar">
                            <?php if(!empty($user['avatar'])): ?>
                                <img src="https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=4243138775745960&amp;height=50&amp;width=50&amp;ext=1627586724&amp;hash=AeSLCgIUa9lNnIBvg_A">
                            <?php endif; ?>
                        </div>

                        <span><?= $user['name'] ?></span>
                        
                        <div class="arrow">
                            <svg width="11" height="8" viewBox="0 0 11 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.834 0h-8a1 1 0 0 0-.8 1.6l4 5.333a1 1 0 0 0 1.6 0l4-5.333a1 1 0 0 0-.8-1.6z" fill="#F16723"></path>
                            </svg>
                        </div>
                    </button>
                    
                    <div class="drop-user">
                        <a href="<?= network_site_url('/login') ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.997 12H14M11 8.999L14 12l-3 3.001" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                
                                <path d="M5.998 9.136V7.398a2 2 0 0 1 1.608-1.96L18.611 3.03a2.147 2.147 0 0 1 2.393 2.167v13.806a2.001 2.001 0 0 1-2.34 1.973L7.659 19.083a2 2 0 0 1-1.661-1.973v-2.138" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            
                            <span>Log out</span>
                        </a>
                        
                        <?php if(!empty($feedback['url'])): ?>
                            <a href="<?= $feedback['url'] ?>" target="<?= !empty($feedback['target']) ? $feedback['target'] : '_self' ?>">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path clip-rule="evenodd" d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M9.65 14.346c2.184 2.184 4.95 3.576 6.595 1.938l.399-.399a1.201 1.201 0 0 0-.16-1.84c-.39-.271-.807-.562-1.27-.887a1.213 1.213 0 0 0-1.547.128l-.451.448a9.67 9.67 0 0 1-1.626-1.322l-.002-.002a9.63 9.63 0 0 1-1.322-1.626l.448-.451c.412-.414.463-1.07.128-1.548-.326-.462-.617-.88-.887-1.269a1.203 1.203 0 0 0-1.84-.16l-.4.399C6.08 9.4 7.47 12.164 9.654 14.349" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                
                                <span><?= $feedback['title'] ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if(!empty($privacy_policy['url'])): ?>
                            <a href="<?= $privacy_policy['url'] ?>" target="<?= !empty($privacy_policy['target']) ? $privacy_policy['target'] : '_self' ?>">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path clip-rule="evenodd" d="M18.414 6.414l-2.828-2.828A2 2 0 0 0 14.172 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7.828a2 2 0 0 0-.586-1.414z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    
                                    <path d="M19 8h-4a1 1 0 0 1-1-1V3M9.5 17h5M14 11.5L11.5 14 10 12.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                
                                <span><?= $privacy_policy['title'] ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>