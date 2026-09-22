<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings\Exceptions;

/**
 * Is thrown when a storageName cannot be found in the initialized storages
 */
class StorageNotFoundException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
