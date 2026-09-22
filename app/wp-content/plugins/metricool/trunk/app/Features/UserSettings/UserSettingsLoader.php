<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings;

use Metricool\Features\AbstractLoader;
use Metricool\Traits\HasAllowlistControl;

class UserSettingsLoader extends AbstractLoader
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
     * @inheritDoc
     */
    public function inScope(): bool
    {
        return $this->userCanManage();
    }
}
