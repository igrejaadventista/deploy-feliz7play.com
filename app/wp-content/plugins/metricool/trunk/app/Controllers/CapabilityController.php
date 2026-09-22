<?php

declare(strict_types=1);

namespace Metricool\Controllers;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Interfaces\ControllerInterface;
use Metricool\Services\CapabilityService;

class CapabilityController implements ControllerInterface
{
    private CapabilityService $service;

    public function __construct(CapabilityService $service)
    {
        $this->service = $service;
    }

    public function register(): void
    {
        add_action('metricool_activation', [$this, 'handlePluginActivation']);
        add_action('metricool_plugin_version_upgrade', [$this, 'handlePluginUpgrade'], 10, 2);
        add_action('wp_initialize_site', [$this, 'addCapabilityToNewSubsite'], 10, 2);
    }

    public function handlePluginActivation(): void
    {
        $this->service->addSiteCapability('metricool_manage');
    }

    /**
     * Handle plugin upgrades
     */
    public function handlePluginUpgrade(string $previousVersion, string $newVersion): void
    {
        // If someone upgrades from legacy version we need to add the capability
        if ($previousVersion && version_compare($previousVersion, '2.0', '<')) {
            $this->service->addSiteCapability('metricool_manage');
        }
    }

    /**
     * When a new site is added, add our custom capability
     */
    public function addCapabilityToNewSubsite(\WP_Site $newSite, array $args): void
    {
        switch_to_blog((int) $newSite->blog_id);
        $this->service->addSiteCapability('metricool_manage', false);
        restore_current_blog();
    }
}
