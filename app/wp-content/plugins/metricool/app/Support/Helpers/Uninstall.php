<?php

declare(strict_types=1);

namespace Metricool\Support\Helpers;

use Metricool\Services\OptionsService;

class Uninstall
{
    private OptionsService $options;

    public function __construct(OptionsService $options)
    {
        $this->options = $options;
    }

    /**
     * Handle plugin uninstallation.
     * @internal Method is currently hooked as the uninstallation callback
     * {@see Plugin::boot}
     */
    public function handlePluginUninstall(): void
    {
        $this->deleteAllOptions();
        $this->deleteCapability();
    }

    /**
     * Delete all Plugin options from wp_options
     */
    private function deleteAllOptions(): void
    {
        $this->options->wipe(true);
    }

    /**
     * Remove the 'metricool_manage' capability from the administrator role if it exists
     */
    private function deleteCapability(): void
    {
        $role = get_role('administrator');
        if ($role && $role->has_cap('metricool_manage')) {
            $role->remove_cap('metricool_manage');
        }
    }
}
