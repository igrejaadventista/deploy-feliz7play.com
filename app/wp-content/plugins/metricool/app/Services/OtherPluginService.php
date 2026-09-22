<?php

declare(strict_types=1);

namespace Metricool\Services;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Support\Helpers\Storage;

class OtherPluginService
{
    /**
     * Should be a Storage object based on one entry in the plugins config
     * {@see config/plugins.php}
     */
    protected Storage $pluginConfig;

    /**
     * @param array $pluginConfig Optional so {@see setPluginConfig} can be skipped
     */
    public function __construct(array $pluginConfig = [])
    {
        $this->pluginConfig = new Storage($pluginConfig);
    }

    /**
     * Use this method as the default way to set the plugin config. For an
     * example see {@see \Metricool\Http\Endpoints\OtherPluginEndpoints}
     */
    public function setPluginConfig(array $pluginConfig): void
    {
        $this->pluginConfig = new Storage($pluginConfig);
    }

    /**
     * Method returns the url fitting for the context. If a plugin is
     * upgradable, the upgrade_url is returned, otherwise the url entry.
     */
    public function getPluginUrl(): string
    {
        if ($this->pluginCanBeUpgraded()) {
            return $this->pluginConfig->getUrl('upgrade_url');
        }

        return $this->pluginConfig->getUrl('url');
    }

    /**
     * Method returns if the plugin is active.
     */
    public function getPluginActive(): bool
    {
        return $this->pluginIsActive() || $this->premiumPluginIsInstalled();
    }

    /**
     * Method returns the action fitting for the context of the plugin.
     */
    public function getAvailablePluginAction(): string
    {
        if ($this->premiumPluginIsInstalled()) {
            return 'installed';
        }

        if ($this->pluginIsDownloadable()) {
            return 'download';
        }

        if ($this->pluginCanBeActivated()) {
            return 'activate';
        }

        if ($this->pluginCanBeUpgraded()) {
            return 'upgrade-to-premium';
        }

        return 'installed';
    }

    /**
     * Execute action for another plugin
     * @throws \Exception If the plugin info could not be retrieved
     */
    public function executeAction(string $action): void
    {
        if (current_user_can('install_plugins') === false) {
            return;
        }

        ob_start();

        switch ($action) {
            case 'download':
                $this->downloadCurrentPlugin();
                break;
            case 'activate':
                $this->activateCurrentPlugin();
                break;
            default:
                break;
        }

        ob_get_clean();
    }

    /**
     * Download the other plugin currently stored in the plugin config
     * property.
     * @throws \Exception If the plugin info could not be retrieved
     */
    protected function downloadCurrentPlugin(): bool
    {
        if (!current_user_can('install_plugins')) {
            return false;
        }

        $transientName = 'rsp_plugin_download_active';
        if (get_transient($transientName) === $this->pluginConfig->getString('slug')) {
            return true;
        }

        set_transient($transientName, $this->pluginConfig->getString('slug'), MINUTE_IN_SECONDS);

        try {
            $pluginInfo = $this->getCurrentPluginInfo();
        } catch (\Exception $e) {
            return false;
        }

        $downloadLink = esc_url_raw($pluginInfo->versions['trunk']);

        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        $skin = new \WP_Ajax_Upgrader_Skin();
        $upgrader = new \Plugin_Upgrader($skin);
        $result = $upgrader->install($downloadLink);

        if (is_wp_error($result)) {
            return false;
        }

        delete_transient($transientName);
        return true;
    }

    /**
     * Activate the other plugin currently stored in the plugin config
     * property.
     */
    protected function activateCurrentPlugin(): bool
    {
        if (!current_user_can('install_plugins')) {
            return false;
        }

        $slug = $this->pluginConfig->getString('activation_slug');

        //when activated from the network admin, we assume the user wants network activated
        $networkwide = is_multisite() && is_network_admin();
        if (!defined('DOING_CRON')) {
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound -- DOING_CRON is a WordPress core constant.
            define('DOING_CRON', true);
        }

        if (!function_exists('activate_plugin')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $result = activate_plugin($slug, '', $networkwide);
        if (is_wp_error($result)) {
            return false;
        }

        $this->cancelShepherdTour();
        return true;
    }

    /**
     * Helper method to check if the current plugin is a premium plugin and if
     * it is active.
     */
    protected function premiumPluginIsInstalled(): bool
    {
        return $this->pluginConfig->has('constant_premium')
            && defined($this->pluginConfig->getString('constant_premium'));
    }

    /**
     * Helper method to check if the current plugin is downloadable.
     */
    protected function pluginIsDownloadable(): bool
    {
        return $this->pluginFileExists() === false;
    }

    /**
     * Helper method to check if the current plugin can be activated.
     */
    protected function pluginCanBeActivated(): bool
    {
        return $this->pluginFileExists() && ($this->pluginIsActive() === false);
    }

    /**
     * Helper method to check if the current plugin can be upgraded. This means
     * the premium version is downloaded, but not yet activated.
     */
    protected function pluginCanBeUpgraded(): bool
    {
        return $this->pluginConfig->has('constant_premium')
            && !defined($this->pluginConfig->getString('constant_premium'));
    }

    /**
     * Helper method to check if the current plugin file exists.
     */
    protected function pluginFileExists(): bool
    {
        return file_exists(trailingslashit(WP_PLUGIN_DIR) . $this->pluginConfig->getString('activation_slug'));
    }

    /**
     * Helper method to check if the current plugin is active.
     */
    public function pluginIsActive(): bool
    {
        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return is_plugin_active($this->pluginConfig->getString('activation_slug'));
    }

    /**
     * Method returns the plugin info for the current plugin. Because we pass
     * the action 'plugin_information' to the plugins_api function, an object is
     * returned if the plugin is found, otherwise a WP_Error.
     * @throws \Exception If the plugin info could not be retrieved
     */
    protected function getCurrentPluginInfo(): object
    {
        $transientName = 'rsp_' . $this->pluginConfig->getString('slug') . '_plugin_info';
        $pluginInfo = get_transient($transientName);

        if (!empty($pluginInfo)) {
            return $pluginInfo;
        }

        if (function_exists('plugins_api') === false) {
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }

        $pluginInfo = plugins_api('plugin_information', [
            'slug' => $this->pluginConfig->getString('slug'),
        ]);

        if (is_wp_error($pluginInfo)) {
            throw new \Exception('Unable to get plugin info');
        }

        set_transient($transientName, $pluginInfo, WEEK_IN_SECONDS);
        return $pluginInfo;
    }

    /**
     * Cancel shepherd tour
     * @todo - This should be moved to a separate service as its not specific to
     * this class. Following SRP principle.
     */
    public function cancelShepherdTour(): void
    {
        $prefix = $this->pluginConfig->getString('options_prefix');
        update_site_option($prefix . '_tour_started', false);
        update_site_option($prefix . '_tour_shown_once', true);
        delete_transient($prefix . '_redirect_to_settings');
    }
}
