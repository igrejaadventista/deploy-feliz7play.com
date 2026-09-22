<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool\Entities;

use Metricool\Vendor\Carbon\Carbon;
use Metricool\Support\Helpers\Collection;
use Metricool\Http\Metricool\DTOs\DistributionDTO;
use Metricool\Http\Metricool\MetricoolClient;
use Metricool\Http\Metricool\Traits\IsFilterable;
use Metricool\Traits\IsHydratable;

/**
 * API responses for distribution statistics include data on how various metrics
 * are distributed. Such as page views by country, referrer pages, or traffic
 * sources.
 */
class DistributionStatistics
{
    use IsFilterable;
    use IsHydratable;

    protected MetricoolClient $client;
    protected string $endpoint = 'stats/distribution/';
    protected string $metric;

    /**
     * The distribution statistics API is compatible with these metrics.
     */
    private array $metrics = [
        'country',
        'referers',
        'sources',
    ];

    /**
     * Pass a compatible metric to the constructor: {@see metrics}
     * @throws \InvalidArgumentException
     */
    public function __construct(MetricoolClient $client, string $metric, bool $filterRequired = true)
    {
        if (!in_array($metric, $this->metrics)) {
            throw new \InvalidArgumentException(esc_html("Incompatible metric given: $metric"));
        }

        $this->metric = $metric;
        $this->client = $client;
        $this->endpoint .= $metric;
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
            'start' => '/^\d+$/', // Just digits
            'end' => '/^\d+$/', // Just digits
            'country' => '/^[a-z]{2}$/', // ISO 3166-1 alpha-2 lowercase country code
        ];
    }


    /**
     * Hydrate every result into a DistributionDTO object
     */
    protected function hydrateItem($key, $item): DistributionDTO
    {
        return new DistributionDTO($this->metric, $key, $item);
    }

    /**
     * Fetch and return the distribution statistics data
     */
    public function get(): Collection
    {
        if ($this->requiresFilter && $this->filtered === false) {
            $this->filter($this->filters);
        }

        return $this->hydrateResults($this->client->get($this->endpoint));
    }
}
