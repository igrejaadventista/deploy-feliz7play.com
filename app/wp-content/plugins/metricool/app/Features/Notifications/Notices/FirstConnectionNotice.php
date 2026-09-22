<?php

declare(strict_types=1);

namespace Metricool\Features\Notifications\Notices;

use Metricool\Support\Helpers\MetricoolUrl;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class FirstConnectionNotice extends AbstractNotice
{
    public const IDENTIFIER = 'first_connection';
    protected bool $active = true;

    private EnvironmentConfig $env;

    public function __construct(EnvironmentConfig $env)
    {
        $this->env = $env;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return __('Get the most out of Metricool', 'metricool');
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        return __('Connect your first social account to Metricool to start scheduling and tracking your content.', 'metricool');
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return self::TYPE_INFO;
    }

    /**
     * @inheritDoc
     */
    public function getRoute(): string
    {
        return 'connections';
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
