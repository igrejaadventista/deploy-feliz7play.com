<?php

declare(strict_types=1);

namespace Metricool\Features\TaskManagement\Tasks;

// todo - add listener
class UnableToTrackTrafficTask extends AbstractTask
{
    public const IDENTIFIER = 'unable_to_track_traffic';

    /**
     * This task is hidden by default as a user should have a working Metricool
     * script by default. Only show the task if it has an active state, never
     * in a completed state. That looks weird while filtering.
     */
    public function __construct()
    {
        $this->hide();
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        return __('Unable to track traffic on your site', 'metricool');
    }

    /**
     * @inheritDoc
     */
    public function getAction(): array
    {
        // todo - fetch from settings
        $queryArgs = array_filter([
            'blogId' => (defined('METRICOOL_BLOG_ID') ? METRICOOL_BLOG_ID : ''),
            'userId' => (defined('METRICOOL_USER_ID') ? METRICOOL_USER_ID : ''),
        ]);

        $link = add_query_arg($queryArgs, 'https://app.metricool.com/evolution/web');

        return [
            'text' => __('Validate connection', 'metricool'),
            'link' => $link,
            'target' => '_blank',
        ];
    }
}
