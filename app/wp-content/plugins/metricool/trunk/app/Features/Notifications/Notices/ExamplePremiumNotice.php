<?php

declare(strict_types=1);

namespace Metricool\Features\Notifications\Notices;

class ExamplePremiumNotice extends AbstractNotice
{
    public const IDENTIFIER = 'example_premium_notice';
    protected bool $active = true;
    protected bool $premium = true;

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return __('This is an premium notice.', 'metricool');
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        return __('This is an premium notice.', 'metricool');
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
        return 'general';
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
