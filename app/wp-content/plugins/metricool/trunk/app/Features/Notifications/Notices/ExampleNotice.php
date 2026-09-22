<?php

declare(strict_types=1);

namespace Metricool\Features\Notifications\Notices;

class ExampleNotice extends AbstractNotice
{
    public const IDENTIFIER = 'example_notice';
    protected bool $active = true;

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return __('This is a notice without a route.', 'metricool');
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        return __('This is a notice without a route.', 'metricool');
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
        return self::ALL_ROUTES;
    }


    /**
     * @inheritDoc
     */
    public function getAction(): array
    {
        return [
            'text' => __('Example text', 'metricool'),
            'link' => 'https://example.test',
            'target' => '_blank',
        ];
    }
}
