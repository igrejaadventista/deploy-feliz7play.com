<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings\Exceptions;

/**
 * Is thrown when a storing setting fails validation. It contains a list of all the
 * validation errors
 */
class ValidationFailedExceptions extends \RuntimeException
{
    /** @var ValidatorFailedException[] */
    public array $validationErrors = [];

    public function __construct(array $validationErrors)
    {
        parent::__construct('The validation of the settings failed');

        $this->validationErrors = $validationErrors;
    }

    public function getErrors(): array
    {
        $errors = [];
        foreach ($this->validationErrors as $fieldName => $error) {
            $errors[$fieldName] = [
                'message' => $error->getMessage()
            ];
        }
        return $errors;
    }
}
