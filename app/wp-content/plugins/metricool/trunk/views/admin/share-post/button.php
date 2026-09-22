<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Variables that should be passed to the view
 * @var string $actionableUrl
 * @var string $label
 */
?>

<a href="<?php echo esc_url($actionableUrl) ?>" class="button button-primary" target="_blank">
    <?php echo esc_html($label); ?>
    <span class="dashicons dashicons-megaphone" style="line-height: 100%; vertical-align: middle"></span>
</a>
