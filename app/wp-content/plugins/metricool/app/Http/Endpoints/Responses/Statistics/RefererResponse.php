<?php

declare(strict_types=1);

namespace Metricool\Http\Endpoints\Responses\Statistics;

use Metricool\Http\Endpoints\Responses\DistributionResponse;
use Metricool\Http\Metricool\DTOs\DistributionDTO;

class RefererResponse extends DistributionResponse
{
    /**
     * @inheritDoc
     */
    public function parseSingleItem(DistributionDTO $item, int $total): object
    {
        return (object) [
            'url' => $item->value,
            'pageViews' => $item->amount,
            'percentage' => $item->calculatePercentageFromTotal($total),
        ];
    }
}
