<?php

declare(strict_types=1);

namespace Metricool\Services;

class TrackingScriptService
{
    public const OPTION_TRACKING_HASH = 'metricool_tracking_script_hash';
    public const OPTION_TRACKING_ACTIVE = 'metricool_tracking_script_active';

    /**
     * Stores the tracking hash in the database
     */
    public function storeTrackingHash(string $hash): self
    {
        update_option(self::OPTION_TRACKING_HASH, $hash, true);

        return $this;
    }

    public function activateTrackingWidget(): self
    {
        update_option(self::OPTION_TRACKING_ACTIVE, true, true);

        return $this;
    }

    /**
     * Returns if the user has enabled the widget in the settings
     */
    public function isTrackingWidgetActive(): bool
    {
        return (bool) get_option(self::OPTION_TRACKING_ACTIVE, false);
    }

    /**
     * Returns the hash from settings
     */
    public function getTrackingHash(): string
    {
        return (string) get_option(self::OPTION_TRACKING_HASH, '');
    }
}
