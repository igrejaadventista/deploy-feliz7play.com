<?php

declare(strict_types=1);

namespace Metricool\Features\TaskManagement\Exceptions;

/**
 * Thrown when trying to dismiss a required task.
 */
class DismissRequiredTaskException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Task is required and cannot be dismissed.');
    }
}
