<?php

declare(strict_types=1);

namespace Metricool\Support\Utility;

/**
 * Utility class for String manipulation.
 */
class StringUtility
{
    /**
     * Convert a URL to a title.
     *
     * Strips the site URL from the given URL, replaces dashes with spaces,
     * and capitalizes the first letter.
     */
    public static function convertUrlToTitle(string $url): string
    {
        // Strip off the page url from the page name
        $site_url = trailingslashit(get_site_url());
        $title = str_replace($site_url, '', $url);
        $title = str_replace('-', ' ', $title);

        // Enforce first letter uppercase
        return ucfirst($title);
    }

    /**
     * Convert a string from snake_case to PascalCase.
     */
    public static function snakeToPascalCase(string $string): string
    {
        return str_replace('_', '', ucwords($string, '_'));
    }

    /**
     * Convert a string from snake_case to camelCase.
     */
    public static function snakeToCamelCase(string $string): string
    {
        return lcfirst(self::snakeToPascalCase($string));
    }

    /**
     * Convert a string from camelCase to snake_case.
     */
    public static function camelToSnakeCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    /**
     * Checks if the string is truly empty and not just a falsy value like '0'
     * or 'false'.
     */
    public static function isEmptyValue(string $string): bool
    {
        return empty($string) && $string !== '0';
    }
}
