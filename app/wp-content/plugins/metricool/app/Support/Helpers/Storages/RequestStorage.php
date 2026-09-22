<?php

declare(strict_types=1);

namespace Metricool\Support\Helpers\Storages;

use Metricool\Support\Helpers\Storage;

/**
 * General config helper used in DI container.
 */
final class RequestStorage extends Storage
{
    public function __construct()
    {
        parent::__construct([
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification is handled at the endpoint level.
            'global' => $_REQUEST,
            // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification is handled at the endpoint level.
            'files' => $_FILES,
        ]);
    }
}
