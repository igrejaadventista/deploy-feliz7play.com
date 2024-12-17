<?php

// Code generated by OpenAPI Generator (https://openapi-generator.tech), manual changes will be lost - read more on https://github.com/algolia/api-clients-automation. DO NOT EDIT.

namespace Algolia\AlgoliaSearch\Model\Recommend;

/**
 * TrendingFacetsModel Class Doc Comment.
 *
 * @category Class
 *
 * @description Trending facet values model.  This model recommends trending facet values for the specified facet attribute.
 */
class TrendingFacetsModel
{
    /**
     * Possible values of this enum.
     */
    public const TRENDING_FACETS = 'trending-facets';

    /**
     * Gets allowable values of the enum.
     *
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::TRENDING_FACETS,
        ];
    }
}