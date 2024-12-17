<?php

// Code generated by OpenAPI Generator (https://openapi-generator.tech), manual changes will be lost - read more on https://github.com/algolia/api-clients-automation. DO NOT EDIT.

namespace Algolia\AlgoliaSearch\Model\Analytics;

/**
 * Direction Class Doc Comment.
 *
 * @category Class
 */
class Direction
{
    /**
     * Possible values of this enum.
     */
    public const ASC = 'asc';

    public const DESC = 'desc';

    /**
     * Gets allowable values of the enum.
     *
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::ASC,
            self::DESC,
        ];
    }
}