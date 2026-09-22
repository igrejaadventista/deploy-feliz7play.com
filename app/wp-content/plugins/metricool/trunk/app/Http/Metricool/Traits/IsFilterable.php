<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool\Traits;

use Metricool\Support\Utility\StringUtility;

/**
 * Parent classes should define an endpoint property
 * @property string $endpoint
 */
trait IsFilterable
{
    /**
     * The filters to be applied to the endpoint.
     */
    protected array $filters = [];

    /**
     * Indicates if the endpoint has been filtered.
     */
    protected bool $filtered = false;

    /**
     * Indicates if the endpoint requires filters to be applied. If set to
     * false, the endpoint can be used without filters.
     */
    protected bool $requiresFilter = false;

    /**
     * Define the accepted filters for this entity. The keys are the filter
     * names and the values are regex patterns to validate them.
     * @example [
     *      'filter_name' => '/^regex_pattern$/',
     *      'another_filter' => '/^\d{4}-\d{2}-\d{2}$/',
     * ]
     */
    abstract protected function getAcceptedFilters(): array;

    /**
     * This method is used to add the given filters to the endpoint property.
     * Only filters that are defined in the {@see getAcceptedFilters} method
     * will be added when the value matches the regex pattern.
     *
     * @internal When the parent class has no endpoint property, it will return
     * the current instance without modifying it.
     */
    public function filter(array $filters): self
    {
        if (empty($this->endpoint)) {
            return $this;
        }

        $acceptedFilters = $this->getAcceptedFilters();

        foreach ($filters as $filterName => $filterValue) {
            if (empty($acceptedFilters[$filterName])) {
                continue;
            }

            if ($this->isFilterValid($filterValue, $acceptedFilters[$filterName]) === false) {
                continue;
            }

            $this->doFilter($filterName, $filterValue);

            $this->filtered = true;
        }

        return $this;
    }

    /**
     * Process the filter value based on the pregMatch condition.
     */
    private function isFilterValid(string $filterValue, string $pregMatch): bool
    {
        return (bool) preg_match($pregMatch, $filterValue);
    }

    /**
     * Method used to retrieve the filters used by the parent entity.
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Method used to execute a filter. Calls apply{FilterName}Filter() method when
     * present. Falls back to self::applyFilter();
     */
    private function doFilter(string $filterName, string $filterValue): void
    {
        $filterMethod = 'apply' . StringUtility::snakeToPascalCase($filterName) . 'Filter';

        if (method_exists($this, $filterMethod)) {
            // execute custom filter
            $this->{$filterMethod}($filterValue);
        } else {
            // execute default filter
            $this->applyFilter($filterName, $filterValue);
        }
    }

    /**
     * Takes a filter name and value and append it to the request. Applied
     * filters are stored in $this->filters property for later retrieval via
     * {@see getFilters()}, useful here:
     * {@see \Metricool\Services\Analytics\TrendService::getTrend}
     */
    protected function applyFilter(string $filterName, string $filterValue): void
    {
        $this->endpoint = add_query_arg(
            sanitize_text_field($filterName),
            sanitize_text_field($filterValue),
            $this->endpoint
        );

        // store applied filter to make them retrievable later via getFilters()
        $this->filters[sanitize_text_field($filterName)] = sanitize_text_field($filterValue);
    }

    /**
     * Apply multiple filters at once.
     */
    protected function applyFilters(array $filters): void
    {
        foreach ($filters as $filterName => $filterValue) {
            $this->applyFilter($filterName, $filterValue);
        }
    }
}
