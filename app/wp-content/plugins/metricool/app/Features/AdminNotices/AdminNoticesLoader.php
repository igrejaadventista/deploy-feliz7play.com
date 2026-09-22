<?php

declare(strict_types=1);

namespace Metricool\Features\AdminNotices;

use Metricool\Features\AbstractLoader;
use Metricool\Traits\HasAllowlistControl;

class AdminNoticesLoader extends AbstractLoader
{
    use HasAllowlistControl;

    /**
     * @inheritDoc
     */
    public function isEnabled(): bool
    {
        return true;
    }

    /**
     * Feature enabled for every logged-in user with the Metricool capability
     */
    public function inScope(): bool
    {
        return $this->userCanManage();
    }
}
