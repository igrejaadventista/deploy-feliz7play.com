<?php

declare(strict_types=1);

namespace Metricool\Services;

class CapabilityService
{
    /**
     * The default WordPress roles that are suitable for the custom
     * Metricool capabilities.
     */
    protected array $defaultCapabilityRoles = [
        'administrator'
    ];

    /**
     * Add a user capability to WordPress and add to administrator role
     * @uses apply_filters metricool_add_manage_capability
     */
    public function addSiteCapability(string $capability, bool $handleSubsites = true, array $roles = []): void
    {
        $rolesToAddCapabilityTo = ($roles ?: $this->defaultCapabilityRoles);

        /**
         * Filter: metricool_suitable_custom_capability_roles
         * @param array $rolesToAddCapabilityTo
         * @return array
         */
        $rolesToAddCapabilityTo = apply_filters('metricool_suitable_custom_capability_roles', $rolesToAddCapabilityTo);

        foreach ($rolesToAddCapabilityTo as $roleName) {
            $role = get_role($roleName);
            if ($role && !$role->has_cap($capability)) {
                $role->add_cap($capability);
            }
        }

        // Refresh the current user's cached capabilities so the new cap is
        // available in the same request (e.g. for admin_menu checks).
        wp_get_current_user()->get_role_caps();

        if ($handleSubsites && is_multisite()) {
            $this->addCapabilityToSubsites($capability);
        }
    }

    /**
     * Recursively add a capability to all subsites
     */
    private function addCapabilityToSubsites(string $capability): void
    {
        $sites = get_sites();
        foreach ($sites as $site) {
            switch_to_blog((int) $site->blog_id);
            $this->addSiteCapability($capability, false);
            restore_current_blog();
        }
    }
}
