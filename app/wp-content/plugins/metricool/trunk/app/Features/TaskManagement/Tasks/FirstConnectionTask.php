<?php

declare(strict_types=1);

namespace Metricool\Features\TaskManagement\Tasks;

use Metricool\Support\Helpers\MetricoolUrl;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class FirstConnectionTask extends AbstractTask
{
    public const IDENTIFIER = 'first_connection';

    protected bool $required = false;

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
        return __('Get the most out of Metricool by linking your Social accounts', 'metricool');
    }

    /**
     * @inheritDoc
     */
    public function getAction(): array
    {
        return [
            'text' => __('Connect', 'metricool'),
            'link' => MetricoolUrl::adminUrl($this->env->getUrl('metricool.connect_network_url')),
            'target' => '_blank',
        ];
    }
}
