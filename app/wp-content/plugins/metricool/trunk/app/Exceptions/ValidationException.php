<?php

declare(strict_types=1);

namespace Metricool\Exceptions;

/**
 * Exception thrown when validation of request data fails.
 * @see \Metricool\Support\Validation\Validator
 */
class ValidationException extends RestDataException
{
    protected int $statusCode = 422;

    /**
     * Create a new instance with the given validation errors.
     */
    public static function withErrors(array $errors): self
    {
        $instance = new self(__('Validation failed.', 'metricool'));
        $instance->setData($errors);

        return $instance;
    }

    /**
     * Get the validation errors, keyed by field name.
     */
    public function errors(): array
    {
        return $this->getData();
    }
}
