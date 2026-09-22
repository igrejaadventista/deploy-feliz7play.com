<?php

declare(strict_types=1);

namespace Metricool\Support\Helpers\Storages;

use Metricool\Support\Helpers\Storage;

/**
 * Middleware configuration helper used in DI container.
 */
final class MiddlewareConfig extends Storage
{
    public function __construct()
    {
        parent::__construct(
            require dirname(__FILE__, 5) . '/config/middleware.php'
        );
    }
}
