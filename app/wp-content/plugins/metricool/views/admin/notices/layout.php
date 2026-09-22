<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Standardized admin notice layout view
 *
 * @var string $logoUrl
 * @var string $noticeId
 * @var bool $isDismissable
 * @var bool $isSnoozable
 * @var string $ctaUrl
 * @var string $ctaLabel
 * @var string $restUrl
 * @var string $content
 * @var string $nonce
 * @var string $wpNonce
 */
?>
<div id="rsp-metricool-notice-<?php echo esc_attr($noticeId); ?>"
     class="fade notice rsp-metricool-admin-notice <?php echo is_rtl() ? 'rtl' : 'ltr'; ?>"
     data-rest-url="<?php echo esc_url($restUrl); ?>"
     data-nonce="<?php echo esc_attr($nonce); ?>"
     data-wp-nonce="<?php echo esc_attr($wpNonce); ?>">
    <div class="rsp-metricool-container">
        <div class="rsp-metricool-admin-notice-image">
            <img src="<?php echo esc_url($logoUrl); ?>" alt="metricool-logo">
        </div>
        <div class="rsp-metricool-content">
            <?php if ($isDismissable) : ?>
                <button type="button" class="rsp-metricool-dismiss" aria-label="<?php esc_attr_e('Dismiss', 'metricool'); ?>" data-notice-action="dismiss">
                    <span class="dashicons dashicons-no-alt"></span>
                </button>
            <?php endif; ?>
            <?php
            // render the content of the notice
            echo wp_kses_post($content);
            ?>
            <div class="rsp-metricool-buttons-row">
                <?php if (!empty($ctaUrl)) : ?>
                    <a class="button" href="<?php echo esc_url($ctaUrl); ?>">
                        <?php echo esc_html($ctaLabel); ?>
                    </a>
                <?php endif; ?>
                <?php if ($isSnoozable) : ?>
                    <button type="button" class="link" data-notice-action="snooze">
                        <?php esc_html_e('Remind me later', 'metricool'); ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
