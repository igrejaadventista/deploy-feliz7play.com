<?php

declare(strict_types=1);

namespace Metricool\Support\Helpers\Storages;

use Metricool\Support\Helpers\Storage;

/**
 * User Settings configuration helper used in DI container.
 */
final class UserSettingsConfig extends Storage
{
    public function __construct()
    {
        parent::__construct(
            require dirname(__FILE__, 5) . '/config/user_settings.php'
        );
    }
}
