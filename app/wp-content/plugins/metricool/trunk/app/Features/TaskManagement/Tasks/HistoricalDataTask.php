<?php

declare(strict_types=1);

namespace Metricool\Features\TaskManagement\Tasks;

use Metricool\Support\Helpers\MetricoolUrl;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class HistoricalDataTask extends AbstractTask
{
    public const IDENTIFIER = 'store_historical_data';

    /**
     * @inheritDoc
     */
    protected bool $required = true;

    /**
     * @inheritDoc
     */
    protected bool $premium = true;

    private EnvironmentConfig $env;

    public function __construct(EnvironmentConfig $env)
    {
        $this->env = $env;
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        return __('Gain access to analytics with unlimited historical data.', 'metricool');
    }

    /**
     * @inheritDoc
     */
    public function getAction(): array
    {
        return [
            'text' => __('Upgrade', 'metricool'),
            'link' => MetricoolUrl::adminUrl($this->env->getUrl('metricool.upgrade_premium_url')),
            'target' => '_blank',
        ];
    }
}
