<?php

declare(strict_types=1);

namespace Metricool\Features\Notifications;

use Metricool\Features\AbstractLoader;

class NotificationsLoader extends AbstractLoader
{
    /**
     * @inheritDoc
     */
    public function isEnabled(): bool
    {
        // todo
        // return (bool) get_option('metricool_onboarding_completed', false);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function inScope(): bool
    {
        return true;
    }
}
