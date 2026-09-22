<?php

declare(strict_types=1);

namespace Metricool\Traits;

trait HasUserAccess
{
    /**
     * Return the first name of the current user
     */
    protected function getCurrentUserFirstName(): string
    {
        $cacheName = 'metricool_current_user_first_name';
        $cacheGroup = 'metricool_has_user_access';
        $cacheValue = wp_cache_get($cacheName, $cacheGroup);

        if (!empty($cacheValue)) {
            return $cacheValue;
        }

        $firstName = '';
        $user = wp_get_current_user();

        if (!empty($user->first_name)) {
            $firstName = ucfirst($user->first_name);
        }

        if (empty($firstName) && !empty($user->user_nicename)) {
            $firstName = ucfirst($user->user_nicename);
        }

        if (empty($firstName) && !empty($user->display_name)) {
            $firstName = ucfirst($user->display_name);
        }

        wp_cache_set($cacheName, $firstName, $cacheGroup);
        return $firstName;
    }
}
