<?php

declare(strict_types=1);

namespace Metricool\Features\TaskManagement\Tasks;

use Metricool\Support\Helpers\MetricoolUrl;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class LinkedInTask extends AbstractTask
{
    public const IDENTIFIER = 'connect_linkedin';

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
        return __('Connect your LinkedIn account', 'metricool');
    }

    /**
     * @inheritDoc
     */
    public function getAction(): array
    {
        return [
            'text' => __('Connect', 'metricool'),
            'link' => MetricoolUrl::adminUrl($this->env->getUrl('metricool.connect_linkedin_url')),
            'target' => '_blank',
        ];
    }
}
