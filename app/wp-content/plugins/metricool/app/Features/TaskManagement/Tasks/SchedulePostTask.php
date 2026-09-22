<?php

declare(strict_types=1);

namespace Metricool\Features\TaskManagement\Tasks;

class SchedulePostTask extends AbstractTask
{
    public const IDENTIFIER = 'schedule_post';

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        return __('Schedule a post to promote your site!', 'metricool');
    }

    /**
     * @inheritDoc
     */
    public function getAction(): array
    {
        return [
            'text' => __('Schedule a post', 'metricool'),
            'link' => admin_url('edit.php'),
        ];
    }
}
