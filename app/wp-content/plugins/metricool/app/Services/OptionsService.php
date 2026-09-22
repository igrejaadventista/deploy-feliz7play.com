<?php

namespace Metricool\Services;

class OptionsService
{
    /**
     * Delete all plugin options from the wp_options table
     * @param bool $private Whether to delete private options (prefixed with _)
     * @param string[] $exclude Exclude specific options from deletion
     */
    public function wipe(bool $private = false, array $exclude = []): bool
    {
        global $wpdb;

        $excludeSql = '';
        foreach ($exclude as $optionName) {
            $excludeSql .= $wpdb->prepare(' AND option_name != %s', $optionName);
        }

        if ($private) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared -- Bulk delete has no WP API equivalent; cache is flushed below. Preserve clause is prepared above.
            $result = $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$wpdb->options} WHERE (option_name LIKE %s OR option_name LIKE %s)",
                    'metricool_%',
                    '_metricool_%'
                ) . $excludeSql
            );
        } else {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared -- Bulk delete has no WP API equivalent; cache is flushed below. Preserve clause is prepared above.
            $result = $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                    'metricool_%'
                ) . $excludeSql
            );
        }

        // Make sure deleted options are not cached
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }

        return $result !== false;
    }
}
