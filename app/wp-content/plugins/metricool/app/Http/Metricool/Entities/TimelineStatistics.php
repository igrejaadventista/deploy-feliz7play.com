<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool\Entities;

use Metricool\Vendor\Carbon\Carbon;
use Metricool\Http\Metricool\Exceptions\ApiException;
use Metricool\Support\Helpers\Collection;
use Metricool\Http\Metricool\DTOs\TimelineDTO;
use Metricool\Http\Metricool\MetricoolClient;
use Metricool\Http\Metricool\Traits\IsFilterable;
use Metricool\Traits\IsHydratable;
use Metricool\Http\Metricool\Filters\PeriodFilter;

/**
 * API responses for the timeline statistics contain of array entries where each
 * entry reflects a timestamp and its corresponding value for the metric.
 * Example: [
 *  "1752170400000",
 *  "981.0"
 * ]
 */
class TimelineStatistics
{
    use IsFilterable;
    use IsHydratable;

    protected string $metric;
    protected MetricoolClient $client;

    public string $endpoint = 'stats/timeline/';

    /**
     * The timeline statistics API is compatible with these metrics.
     */
    private array $compatibleMetrics = [
        'PageViews',
        'SessionsCount',
        'Visitors',
        'DailyPosts',
        'DailyComments',
    ];

    /**
     * Pass a compatible metric to the constructor: {@see compatibleMetrics}
     * @throws \InvalidArgumentException
     */
    public function __construct(MetricoolClient $client, string $metric, bool $filterRequired = true)
    {
        $this->metric = $metric;

        if (!in_array($this->metric, $this->compatibleMetrics)) {
            throw new \InvalidArgumentException(esc_html("Incompatible metric given: $this->metric"));
        }

        $this->client = $client;
        $this->endpoint .= $this->metric;
        $this->requiresFilter = $filterRequired;

        /**
         * The distribution statistics API need a filter by default to prevent
         * Internal Server errors on the remote server. We set the default
         * filters to the last 30 days.
         */
        $this->filters = [
            'start' => Carbon::now()->subDays(30)->format('Ymd'),
            'end' => Carbon::now()->format('Ymd'),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getAcceptedFilters(): array
    {
        return [
            'start' => '/^\d+$/',
            'end' => '/^\d+$/',
            'period' => '/^[a-z0-9]+$/i',
        ];
    }

    /**
     * Applies the period filter
     * @see \Metricool\Http\Metricool\Traits\IsFilterable::doFilter
     */
    protected function applyPeriodFilter(string $period): void
    {
        $filter = PeriodFilter::parse($period, true);
        $this->applyFilters([
            'start' => $filter->getStartDate()->format('Ymd'),
            'end' => $filter->getEndDate()->format('Ymd'),
        ]);
    }

    /**
     * Hydrates a result:
     * [
     *   "1752170400000",
     *   "981.0"
     * ]
     * Into a TimelineStatisticDTO object.
     */
    protected function hydrateItem($key, $item): TimelineDTO
    {
        return (new TimelineDTO((int) $item[0], (float) $item[1]));
    }

    /**
     * Fetch and return the timeline statistics data plainly from the API.
     * @return Collection|TimelineDTO[]
     * @throws ApiException
     */
    public function get(): Collection
    {
        if ($this->requiresFilter && $this->filtered === false) {
            $this->filter($this->filters);
        }

        $results = $this->client->get($this->endpoint);

        if ($this->isEmptyResponse($results)) {
            return new Collection([]);
        }

        $results = $this->hydrateResults($results);

        return $results;
    }

    /**
     * Return the metric that the current instance is used for. Useful for
     * dynamic retrieval of the intent of the instance. For an example see:
     * {@see \Metricool\Services\AnalyticsService::getTrend}
     */
    public function getMetric(): string
    {
        return $this->metric;
    }

    /**
     * When this endpoint holds no data, Metricool returns a result with
     * non-standard output. Just return an empty response when only 1 row is
     * found in the results and the value is 0
     * @param mixed $response
     */
    protected function isEmptyResponse($response): bool
    {
        return empty($response) || (is_array($response) && count($response) === 1 && empty($response[0][1]));
    }
}
