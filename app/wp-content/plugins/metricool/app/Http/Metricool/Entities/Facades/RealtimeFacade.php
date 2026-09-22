<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool\Entities\Facades;

use Metricool\Http\Metricool\MetricoolClient;
use Metricool\Http\Metricool\Entities\RealtimeStatistics;

class RealtimeFacade
{
    protected MetricoolClient $client;

    public function __construct(MetricoolClient $client)
    {
        $this->client = $client;
    }

    public function current(): RealtimeStatistics
    {
        return new RealtimeStatistics($this->client, 'values');
    }

    public function sessions(): RealtimeStatistics
    {
        return new RealtimeStatistics($this->client, 'sessions');
    }

    public function pageViewsPerHour(): RealtimeStatistics
    {
        return new RealtimeStatistics($this->client, 'pvperhour');
    }

    public function pageViews(): RealtimeStatistics
    {
        return new RealtimeStatistics($this->client, 'distribution/currentpageviews');
    }

    public function referers(): RealtimeStatistics
    {
        return new RealtimeStatistics($this->client, 'distribution/referers');
    }

    public function countries(): RealtimeStatistics
    {
        return new RealtimeStatistics($this->client, 'distribution/countries');
    }

    public function sources(): RealtimeStatistics
    {
        return new RealtimeStatistics($this->client, 'distribution/sources');
    }
}
