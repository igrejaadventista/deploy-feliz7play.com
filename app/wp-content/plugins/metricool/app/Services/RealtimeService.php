<?php

declare(strict_types=1);

namespace Metricool\Services;

use Metricool\Support\Builders\StatsTimelineBuilder;
use Metricool\Support\Helpers\Collection;
use Metricool\Http\Metricool\DTOs\TimelineDTO;

class RealtimeService
{
    /**
     * @var array<string, array{
     *     name: string,
     *     label: string,
     *     results: Collection|TimelineDTO[],
     *     useInTimeline: bool,
     *  }> Metrics holds the name, label and results of the metric
     **/
    protected array $metrics = [];
    /**
     * @var array<string, array{
     *     label: string,
     *     totalAmount: int,
     *  }> Totals holds the values to be shown in the totals
     **/
    protected array $totals = [];

    /**
     * Sets the metrics to be used in the realtime service. The metrics contains the name, label and results of each metric.
     */
    public function addMetric(string $metric, string $label, array $results, bool $useInTimeline = true, bool $useInTotals = true): self
    {
        $results = $this->hydrateResults($results)->sortBy('timestamp');

        if ($useInTimeline) {
            $this->metrics[$metric] = [
                'name' => $metric,
                'label' => $label,
                'results' => $results,
            ];
        }

        if ($useInTotals) {
            $this->addTotals($metric, $label, $results->sum('amount'));
        }

        return $this;
    }

    /**
     * Orders and hydrates the results of a Metricool timeline into a collection of TimelineDTO objects.
     */
    protected function hydrateResults(array $results): Collection
    {
        $collection = new Collection();

        foreach ($results as $timestamp => $amount) {
            $collection->push(
                new TimelineDTO((int) $timestamp, (float) $amount)
            );
        }

        return $collection;
    }

    /**
     * Adds a total to be used in the response
     * @param int|float $amount
     */
    public function addTotals(string $metric, string $label, $amount): void
    {
        $this->totals[$metric] = [
            'label' => $label,
            'totalAmount' => $amount,
        ];
    }

    /**
     * Gets the totals to be used in the response
     */
    public function getTotals(): array
    {
        return $this->totals;
    }

    /**
     * Builds the timeline
     * @see \Metricool\Http\Endpoints\RealtimeEndpoint
     */
    public function getTimelineData(): array
    {
        return (new StatsTimelineBuilder())->setDateFormat('LT')
            ->setMetrics($this->metrics)
            ->build();
    }
}
