<?php

declare(strict_types=1);

namespace Metricool\Features\Notifications\Notices;

use Metricool\Support\Helpers\MetricoolUrl;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class ExampleConnectionsWarning extends AbstractNotice
{
    public const IDENTIFIER = 'example_connections_warning';
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
        return __('This notice is a warning.', 'metricool');
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        return __('This notice is a warning.', 'metricool');
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return self::TYPE_WARNING;
    }

    /**
     * @inheritDoc
     */
    public function getRoute(): string
    {
        return 'general';
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
