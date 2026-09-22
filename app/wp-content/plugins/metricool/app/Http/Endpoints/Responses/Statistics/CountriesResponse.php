<?php

declare(strict_types=1);

namespace Metricool\Http\Endpoints\Responses\Statistics;

use Locale;
use Metricool\Http\Endpoints\Responses\DistributionResponse;
use Metricool\Http\Metricool\DTOs\DistributionDTO;

class CountriesResponse extends DistributionResponse
{
    /**
     * The columns for the country chart. Keys represent the property,
     * value is the label for this property
     * @see \Metricool\Support\Builders\StatsChartTableBuilder::setColumns()
     */
    public function getChartColumns(): array
    {
        return [
            'country' => __('Country', 'metricool'),
            'visitors' => __('Visitors', 'metricool'),
            'percentage' => __('Percentage', 'metricool'),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function parseSingleItem(DistributionDTO $item, int $total): object
    {
        return (object) [
            'country' => Locale::getDisplayRegion('-' . $item->value, get_user_locale()),
            'visitors' => $item->amount,
            'percentage' => $item->calculatePercentageFromTotal($total),
        ];
    }
}
