<?php

declare(strict_types=1);

namespace Metricool\Controllers;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Traits\HasViews;
use Metricool\Traits\HasAllowlistControl;
use Metricool\Interfaces\ControllerInterface;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class AdminController implements ControllerInterface
{
    use HasAllowlistControl;
    use HasViews;

    private EnvironmentConfig $env;

    public function __construct(EnvironmentConfig $env)
    {
        $this->env = $env;
    }

    public function register(): void
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueGeneralAdminStyles']);
        add_filter('plugin_action_links_' . $this->env->getString('plugin.base_file'), [$this, 'addPluginSettingsAction']);
    }

    /**
     * Method enqueues the css for general admin styles.
     */
    public function enqueueGeneralAdminStyles(): void
    {
        wp_enqueue_style(
            'metricool-admin-general-styles',
            $this->env->getUrl('plugin.url') . 'assets/css/admin.css',
            [],
            $this->env->getString('plugin.version')
        );
    }

    /**
     * Adds settings and support link to the plugin page
     */
    public function addPluginSettingsAction(array $links): array
    {
        if ($this->userCanManage() === false) {
            return $links;
        }

        $settings_link = '<a href="' . $this->env->getUrl('plugin.dashboard_url') . '">' . esc_html__('Settings', 'metricool') . '</a>';
        array_unshift($links, $settings_link);

        //support
        $support = '<a rel="noopener noreferrer" target="_blank" href="' . esc_attr($this->env->getUrl('plugin.support_url')) . '">' . esc_html__('Support', 'metricool') . '</a>';
        array_unshift($links, $support);

        return $links;
    }
}
