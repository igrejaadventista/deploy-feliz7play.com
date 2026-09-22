<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool\DTOs;

/**
 * This class represents a TimelineStatistic of one of the results of
 * the TimelineStatistic Entity. Every result of the Metricool timeline can
 * be hydrated into this DTO.
 * {@see \Metricool\Http\Metricool\Entities\TimelineStatistics::hydrateItem()}
 */
class TimelineDTO extends DTO
{
    public int $timestamp;
    public float $amount;

    /**
     * Constructor to fill all the properties of the TimelineStatistic
     */
    public function __construct(int $timestamp, float $amount)
    {
        $this->timestamp = $timestamp;
        $this->amount = $amount;
    }
}
