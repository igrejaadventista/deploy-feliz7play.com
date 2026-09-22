<?php

declare(strict_types=1);

namespace Metricool\Support\Builders;

use Metricool\Support\Helpers\Collection;

/**
 * Builds an array that creates the data for charts.
 * Example:
 * [
 *   [
 *     "value", "Country", "Visitors"
 *   ],
 *   [
 *     "nl", "Netherlands", "121321300"
 *   ],
 *   [
 *     "ar", "Argentina", "22342"
 *   ]
 * ]
 * @see DistributionStatisticsService::getChartsData() for usage example
 */
class StatsChartTableBuilder
{
    /** @var Collection */
    private Collection $results;
    private array $columns;

    /**
     * Sets the columns that holds the property names of the DTO to be used in the chart table.
     * Example:
     * [
     *   'amount' => __('Amount', 'metricool'),
     *   'metric' => __('Visitors', 'metricool')
     * ]
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Sets the results from the DistributionStatistics Entity
     * @param Collection $results
     */
    public function setResults(Collection $results): self
    {
        $this->results = $results;

        return $this;
    }

    /**
     * Build the chart data
     */
    public function build(): array
    {
        $chartTable = [];

        if ($this->results->count() === 0) {
            return $chartTable;
        }

        $chartTable[] = $this->getColumnLabels();

        foreach ($this->results as $result) {
            $chartTable[] = $this->createRow($result);
        }

        return $chartTable;
    }

    /**
     * Returns the row that holds the column labels
     */
    protected function getColumnLabels(): array
    {
        return array_values($this->columns);
    }

    /**
     * Creates a row into the chart based on the chartColumns
     * Each key of the chart column is a property of the DTO
     */
    protected function createRow(object $result): array
    {
        $row = [];

        foreach ($this->columns as $property => $column) {
            $row[] = $result->{$property};
        }

        return $row;
    }
}
