<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings\Exceptions;

/**
 * Is thrown when a Storage fails to store or retrieve a setting
 */
class StorageFailedException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
