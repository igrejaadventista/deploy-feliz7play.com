<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool\Entities\Facades;

use Metricool\Http\Metricool\MetricoolClient;
use Metricool\Http\Metricool\Entities\TimelineStatistics;
use Metricool\Http\Metricool\Entities\DistributionStatistics;

/**
 * Facade to access various statistics entities in Metricool.
 */
class StatisticsFacade
{
    protected MetricoolClient $client;

    public function __construct(MetricoolClient $client)
    {
        $this->client = $client;
    }

    /**
     * Returns per-country website visits distribution during the period. Use
     * the {@see filter()} method to filter the results by date range.
     */
    public function countries(): DistributionStatistics
    {
        return new DistributionStatistics($this->client, 'country');
    }

    /**
     * Returns the distribution of website visits by referers during the period.
     * Use the {@see filter()} method to filter the results by date range.
     *
     * @internal 'referers' results in a list of website uri's with the amount
     * of visits from each referer during the period.
     */
    public function referers(): DistributionStatistics
    {
        return new DistributionStatistics($this->client, 'referers');
    }

    /**
     * Returns the distribution of website visits by sources during the period.
     * Use the {@see filter()} method to filter the results by date range.
     *
     * @internal 'sources' results in a list of source-types from which the
     * visits originated, such as 'direct', 'google.com', 'youtube.com', etc.
     */
    public function sources(): DistributionStatistics
    {
        return new DistributionStatistics($this->client, 'sources');
    }

    /**
     * Returns the page view statistics for the website during the period for
     * timeline charts usage.
     */
    public function pageViews(): TimelineStatistics
    {
        return new TimelineStatistics($this->client, 'PageViews');
    }

    /**
     * Returns the session counts for the website during the period for
     * timeline charts usage
     */
    public function visits(): TimelineStatistics
    {
        return new TimelineStatistics($this->client, 'SessionsCount');
    }

    /**
     * Returns the visitor statistics for the website during the period for
     * timeline charts usage.
     */
    public function visitors(): TimelineStatistics
    {
        return new TimelineStatistics($this->client, 'Visitors');
    }

    /**
     * Returns the amount of posts made on the blog during the period for
     * timeline charts usage.
     */
    public function posts(): TimelineStatistics
    {
        return new TimelineStatistics($this->client, 'DailyPosts');
    }

    /**
     * Returns the amount of comments made on the blog during the period for
     * timeline charts usage.
     */
    public function comments(): TimelineStatistics
    {
        return new TimelineStatistics($this->client, 'DailyComments');
    }
}
