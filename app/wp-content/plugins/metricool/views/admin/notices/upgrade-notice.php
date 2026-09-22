<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Inner content for the legacy upgrade admin notice.
 * Rendered inside the layout.php template.
 * CTA and dismiss buttons are rendered by the layout.
 */

?>

<p><strong><?php esc_html_e('You have just upgraded to the new Metricool plugin', 'metricool'); ?></strong></p>
<p>
    <?php
    echo wp_kses_post(
        sprintf(
            /* translators: 1: opening <strong> tag, 2: closing </strong> tag. */
            __('Please %1$ssign in%2$s to discover all new functionality', 'metricool'),
            '<strong>',
            '</strong>'
        )
    );
    ?>
</p>
