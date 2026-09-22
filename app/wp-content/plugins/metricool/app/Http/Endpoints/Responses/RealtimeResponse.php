<?php

declare(strict_types=1);

namespace Metricool\Http\Endpoints\Responses;

class RealtimeResponse extends Response
{
    /**
     * @var array $totals The data to be shown in the totals columns
     * @see \Metricool\Services\RealtimeService::getTotals()
     */
    public array $totals = [];
    /**
     * @var array $timelineData The data to be shown in the timeline
     * @see \Metricool\Services\RealtimeService::getTimelineData()
     */
    public array $timelineData = [];

    public function setTotals(array $totals): self
    {
        $this->totals = $totals;

        return $this;
    }

    public function setTimelineData(array $timeline): self
    {
        $this->timelineData = $timeline;

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
