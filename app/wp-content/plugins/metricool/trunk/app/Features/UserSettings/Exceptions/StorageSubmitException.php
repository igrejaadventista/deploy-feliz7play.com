<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings\Exceptions;

class StorageSubmitException extends \RuntimeException
{
    /**
     * key-value pair of errors occurred during the submit process. The key
     * should reflect the storage name and the value the error message from
     * the client.
     */
    private array $errors = [];

    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
