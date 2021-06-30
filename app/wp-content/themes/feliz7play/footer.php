        <div style="height: 60vh;"></div>
        <?php 
            $logo = get_field('logo_footer', 'site_settings');
            $menu = get_field('footer', 'site_settings');
            $copyright = get_field('copyright', 'site_settings');
            $sites = get_field('sites', 'site_settings');
            $social_networks = get_field('redes_sociais', 'site_settings');
        ?>

        <footer class="footer">
            <div class="main">
                <div class="container-fluid">
                    <div class="left">
                        <?php if(!empty($logo)): ?>
                            <img src="<?= $logo['url'] ?>" alt="Logo" />
                        <?php endif; ?>
                    
                        <?php if(!empty($menu)): ?>
                            <nav>
                                <ul>
                                    <?php foreach($menu as $item): ?>
                                        <li>
                                            <a href="<?= $item['link']['url'] ?>" target="<?= !empty($item['link']['target']) ? $item['link']['target'] : '_self' ?>">
                                                <span><?= $item['link']['title'] ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?> 
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                    
                    <?php if(!empty($social_networks)): ?>
                        <div class="socials">
                            <span>Nossas redes sociais:</span>
                            
                            <ul>
                                <?php foreach($social_networks as $item): ?>
                                    <li>
                                        <a href="<?= $item['url'] ?>" target="_blank" class="f7-icon" rel="noreferrer">
                                            <i class="icon-<?= $item['rede_social'] ?>"></i>
                                        </a>
                                    </li>
                                <?php endforeach; ?>    
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="copy">
                <div class="container-fluid">
                    <?php if(!empty($copyright)): ?>
                        <p><?= $copyright ?></p>
                    <?php endif; ?>
                    
                    <?php if(!empty($sites)): ?>
                        <ul>
                            <?php foreach($sites as $item): ?>
                                <li>
                                    <a href="<?= $item['link']['url'] ?>" target="<?= !empty($item['link']['target']) ? $item['link']['target'] : '_blank' ?>" rel="noreferrer"><?= $item['link']['title'] ?></a>
                                </li>
                            <?php endforeach; ?> 
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </footer>
        
        <?php wp_footer(); ?>
    </body>
</html>