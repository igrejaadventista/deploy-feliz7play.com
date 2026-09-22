<?php

declare(strict_types=1);

namespace Metricool\Services\Analytics;

use Metricool\Vendor\Carbon\Carbon;
use Metricool\Support\Helpers\Collection;
use Metricool\Http\Metricool\Entities\TimelineStatistics;

class TrendService
{
    public const TREND_UP = 'up';
    public const TREND_DOWN = 'down';
    public const TREND_STABLE = 'stable';

    /**
     * Method returns the trend for the given statistic module based on the
     * given filters. To be able to calculate the trend the filters need
     * at least a start and an end date in Ymd format. Otherwise, a
     * 'stable' trend is returned
     *
     * @param array $filters Optional filters to override the filters used on
     * the TimelineStatistics instance.  Must contain 'start' and 'end' keys
     * in Ymd format.
     */
    public function getTrend(TimelineStatistics $statistic, Collection $currentStatistics, array $filters = []): string
    {
        $cacheName = get_class($statistic) . ':' . $statistic->getMetric() . '#' . md5(json_encode($filters));
        $cacheValue = wp_cache_get($cacheName, 'metricool', false, $found);
        if ($found && is_string($cacheValue)) {
            return $cacheValue;
        }

        try {
            $filters = $this->getCurrentPeriodFilters($filters, $statistic);
            $previousStatistics = $statistic->filter(
                $this->getPreviousPeriodFilters($filters)
            )->get();
        } catch (\Throwable $e) {
            return self::TREND_STABLE;
        }

        $trend = $this->calculateTrendFromPeriods($currentStatistics, $previousStatistics);

        wp_cache_set($cacheName, $trend, 'metricool', (5 * MINUTE_IN_SECONDS));
        return $trend;
    }

    /**
     * Method returns filters for the previous period based on the given
     * filters. A period is defined as the difference between start and
     * end date.
     *
     * @param array $filters Must contain 'start' and 'end' keys and should
     * reflect the current period to calculate the previous period from.
     *
     * @throws \InvalidArgumentException When start or end filters are missing
     */
    public function getPreviousPeriodFilters(array $filters): array
    {
        if (empty($filters) || empty($filters['start']) || empty($filters['end'])) {
            throw new \InvalidArgumentException("Filters 'start' and 'end' are required to get the previous period");
        }

        $start = Carbon::createFromFormat('Ymd', $filters['start']);
        $end = Carbon::createFromFormat('Ymd', $filters['end']);
        $diffInDays = $start->diffInDays($end);

        // Previous end is one day before current start
        $previousEnd = $start->copy()->subDay();
        $previousStart = $previousEnd->copy()->subDays($diffInDays);

        return [
            'start' => $previousStart->format('Ymd'),
            'end'   => $previousEnd->format('Ymd'),
        ];
    }

    /**
     * Method is used to calculate the trend based on the sums of the "amount"
     * key from the two given periods.
     *
     * @param Collection $currentPeriod Statistics for the current period
     * @param Collection $previousPeriod Statistics for the previous period
     *
     * @return string One of the TREND_* constants
     */
    private function calculateTrendFromPeriods(Collection $currentPeriod, Collection $previousPeriod): string
    {
        $statisticSumCurrentPeriod = $currentPeriod->sum('amount');
        $statisticSumPreviousPeriod = $previousPeriod->sum('amount');

        if ($statisticSumCurrentPeriod > $statisticSumPreviousPeriod) {
            return self::TREND_UP;
        }

        if ($statisticSumCurrentPeriod < $statisticSumPreviousPeriod) {
            return self::TREND_DOWN;
        }

        return self::TREND_STABLE;
    }

    /**
     * Returns the used 'start' and 'end' filters for the given statistic. Uses
     * the given filters first, and if they are missing, uses the filters from
     * the used statistic instance.
     *
     * @throws \InvalidArgumentException When start or end filters are missing
     * even when using the statistic's filters.
     */
    private function getCurrentPeriodFilters(array $filters, TimelineStatistics $statistic): array
    {
        if (empty($filters['start']) || empty($filters['end'])) {
            $filters = $statistic->getFilters();
        }

        if (empty($filters) || empty($filters['start']) || empty($filters['end'])) {
            throw new \InvalidArgumentException("Filters 'start' and 'end' are required to process statistic filters");
        }

        return $filters;
    }
}
