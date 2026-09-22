<?php

declare(strict_types=1);

namespace Metricool\Features\Notifications\Notices;

class ExampleInactiveNotice extends AbstractNotice
{
    public const IDENTIFIER = 'example_inactive_notice';
    protected bool $active = false;

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return __('This is an inactive notice.', 'metricool');
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        return __('This is an inactive notice.', 'metricool');
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
}
