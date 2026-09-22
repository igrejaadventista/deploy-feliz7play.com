<?php

declare(strict_types=1);

namespace Metricool\Support\Builders;

use Metricool\Vendor\Carbon\Carbon;
use Metricool\Support\Helpers\Collection;
use Metricool\Http\Metricool\DTOs\TimelineDTO;
use Metricool\Http\Metricool\Entities\TimelineStatistics;

/**
 * Builds a timeline from a collection of metrics and their corresponding statistics.
 * @see AnalyticsService for usage example
 */
class StatsTimelineBuilder
{
    /**
     * @var array $timeline This holds all the results of the timeline
     */
    protected array $timeline = [];

    /**
     * Metrics holds the name of the Metric, the label and results of the API request
     * @var array<string, array{
     *     name: string,
     *     label: string,
     *     statistics?: TimelineStatistics,
     *     results?: Collection|TimelineDTO[],
     *     useInTimeline: bool,
     *  }>
     **/
    protected array $metrics = [];

    /**
     * @var string $isoDateFormat The date to be used in the results of the timeline
     */
    protected string $isoDateFormat = 'L';

    /**
     * Combines statistics within the same timestamp data into a timeline.
     * Useful for the dashboard charts.
     */
    public function build(): array
    {
        foreach ($this->metrics as $name => $metric) {
            $statistics = ($metric['results'] ?? []);
            foreach ($statistics as $statistic) {
                if ($this->hasRow($statistic->timestamp) === false) {
                    $this->createRow($statistic->timestamp);
                }

                $this->addMetricToRow($this->timeline[$statistic->timestamp], $name, $statistic);
            }
        }

        return $this->getTimelineRows();
    }

    /**
     * Set the date format to be used in the results of the timeline.
     */
    public function setDateFormat(string $format): self
    {
        $this->isoDateFormat = $format;

        return $this;
    }

    /**
     * Sets the metrics that should be included in a timeline item
     * @param array<string, array{
     *     name: string,
     *     label: string,
     *     statistics?: TimelineStatistics,
     *     results?: Collection|TimelineDTO[],
     *     useInTimeline: bool,
     * }> $metrics
     */
    public function setMetrics(array $metrics): self
    {
        $this->metrics = $metrics;

        return $this;
    }

    /**
     * Returns the timeline without preserving keys.
     */
    public function getTimelineRows(): array
    {
        return array_values($this->timeline);
    }

    /**
     * Returns a row on the given timestamp
     */
    protected function getRow(int $datestamp): ?array
    {
        return $this->timeline[$datestamp] ?? null;
    }

    /**
     * Checks if a row exists on the given timestamp
     */
    protected function hasRow(int $datestamp): bool
    {
        return ($this->getRow($datestamp) !== null);
    }

    /**
     * Creates a row for the given timestamp. Each key in the metrics is a property.
     * This uses the $metrics to create a row which contains a property for
     * every metric with an initial value of 0.
     */
    protected function createRow(int $timestamp): array
    {
        $date = Carbon::createFromTimestampMs($timestamp)->setTimezone(wp_timezone());

        $row = [
            'timestamp' => $timestamp,
            'label' => $date->isoFormat($this->isoDateFormat),
        ];

        // initialize the properties for each metric, these are the keys of the metrics
        foreach ($this->metrics as $property => $metric) {
            $row[$property] = 0.0;
        }

        return $this->addRowToTimeline($timestamp, $row);
    }

    /**
     * Inserts a row to the timeline
     */
    protected function addRowToTimeline(int $timestamp, array $row): array
    {
        $this->timeline[$timestamp] = $row;

        return $this->timeline[$timestamp];
    }

    /**
     * Adds a metric (Visits / PageViews / etc.) to the row
     */
    protected function addMetricToRow(array &$row, string $metric, TimelineDTO $statistic): void
    {
        $row[$metric] = $statistic->amount;
    }
}
