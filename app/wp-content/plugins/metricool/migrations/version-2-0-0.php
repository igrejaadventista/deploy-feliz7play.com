<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Interfaces\MigrationInterface;

return new class () implements MigrationInterface
{
    public function version(): string
    {
        return '2.0.0'; // first version in the 2.x series
    }

    /**
     * Perform schema/data changes required for this migration.
     * @uses add_option to avoid changing existing values.
     */
    public function up(): void
    {
        if (!function_exists('get_option')) {
            require_once ABSPATH . WPINC . DIRECTORY_SEPARATOR . 'option.php';
        }

        // migrate tracking script
        $profileId = trim(get_option('metricool_profile_id', ''));

        if (!empty($profileId)) {
            add_option('metricool_tracking_script_hash', $profileId, '', true);
            add_option('metricool_tracking_script_active', true, '', true);
        }

        delete_option('metricool_profile_id');
    }
};
