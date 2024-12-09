        <?php
        $lang = getLanguage();
        if (!empty($lang)) {
            $languages = get_field('languages', 'site_settings');

            foreach (get_field('languages', 'site_settings') as $language) {
                if ($language['language'] === $lang) {
                    $current_language = $language;
                    break;
                }
            }

            if ($current_language !== null) {
                $logo = $current_language['logo'];
                $menu = $current_language['footer'];
                $copyright = $current_language['copyright'];
                $sites = $current_language['sites'];
                $social_networks = $current_language['social_networks'];
            }
        }
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
                                            <i class="icon-<?= $item['social_network'] ?>"></i>
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
