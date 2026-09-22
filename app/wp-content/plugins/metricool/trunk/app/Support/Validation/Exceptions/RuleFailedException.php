<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Exceptions;

/**
 * This exception is thrown when a validation rule fails. It is caught by the
 * {@see \Metricool\Support\Validation\Validator} which collects the messages
 * per field.
 */
class RuleFailedException extends \RuntimeException
{
}
