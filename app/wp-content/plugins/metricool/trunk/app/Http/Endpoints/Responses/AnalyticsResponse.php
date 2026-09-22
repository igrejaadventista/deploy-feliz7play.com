<?php

declare(strict_types=1);

namespace Metricool\Http\Endpoints\Responses;

class AnalyticsResponse extends Response
{
    /**
     * Sets the totals to be shown in the response
     * @see \Metricool\Services\AnalyticsService::getTotals()
     */
    public array $totals = [];
    /**
     * Sets the totals to be shown in the response
     * @see \Metricool\Services\AnalyticsService::getTimelineData()
     */
    public array $timelineData = [];

    public function setTotals(array $totals): self
    {
        $this->totals = $totals;

        return $this;
    }

    public function setTimelineData(array $timelineData): self
    {
        $this->timelineData = $timelineData;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function body(): array
    {
        return [
            'totals' => $this->totals,
            'timelineData' => $this->timelineData,
        ];
    }
}
