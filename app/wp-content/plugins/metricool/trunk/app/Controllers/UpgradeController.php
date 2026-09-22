<?php

declare(strict_types=1);

namespace Metricool\Controllers;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Interfaces\ControllerInterface;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class UpgradeController implements ControllerInterface
{
    public const LEGACY_VERSION = '1.27';

    private EnvironmentConfig $env;

    public function __construct(EnvironmentConfig $env)
    {
        $this->env = $env;
    }

    public function register(): void
    {
        add_action('metricool_controllers_loaded', [$this, 'checkForUpgrades']);
    }

    /**
     * Fire an action when the plugin is upgraded from one version to another.
     *
     * @internal Note the starting underscore in the option name. This is to
     * prevent the option from being deleted when a user logs out. As if
     * it is a private Metricool option.
     *
     * @hooked metricool_controllers_loaded to make sure Controllers can hook
     * into metricool_plugin_version_upgrade. Even this one.
     *
     * @uses do_action metricool_plugin_version_upgrade Only hook into this
     * action if the upgrade script is related to the responsibility of the
     * hooking class.
     */
    public function checkForUpgrades(): void
    {
        $previousSavedVersion = (string) get_option('_metricool_current_version', '');
        $currentVersion = $this->env->getString('plugin.version');

        if ($previousSavedVersion === $currentVersion) {
            return; // Nothing to do
        }

        // Legacy upgrade
        if (empty($previousSavedVersion) && $this->isLegacyUpgrade()) {
            $previousSavedVersion = self::LEGACY_VERSION;
            do_action('metricool_plugin_legacy_upgrade', $currentVersion);
        }

        do_action('metricool_plugin_version_upgrade', $previousSavedVersion, $currentVersion);
        update_option('_metricool_current_version', $currentVersion, false);
    }

    /**
     * Determine if this is an upgrade from the legacy plugin by checking for the existence of the old metricool_id option.
     */
    private function isLegacyUpgrade(): bool
    {
        return get_option('metricool_profile_id', false) !== false;
    }
}
