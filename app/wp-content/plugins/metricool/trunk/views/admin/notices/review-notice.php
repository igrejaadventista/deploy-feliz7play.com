<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Inner content for the review admin notice.
 * Rendered inside the layout.php template.
 * CTA and snooze buttons are rendered by the layout.
 *
 * @var string $reviewMessage
 */
?>

<?php echo wp_kses_post(wpautop($reviewMessage));
