<?php

declare(strict_types=1);

namespace Metricool\Traits;

trait HasAllowlistControl
{
    /**
     * Check if the current user has the capability to manage the plugin.
     * This is the case when:
     * - The user is logged in and has the 'metricool_manage' capability
     * - This is a REST API request and the user is logged in
     * - This is a WPCLI request
     *
     * @internal This replaces Helper::user_can_manage()
     */
    public function userCanManage(): bool
    {
        // During activation, we need to allow access
        if (get_option('metricool_activation_flag')) {
            return true;
        }

        return current_user_can('metricool_manage');
    }
}
