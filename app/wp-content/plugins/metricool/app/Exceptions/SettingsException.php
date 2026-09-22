<?php

declare(strict_types=1);

namespace Metricool\Exceptions;

class SettingsException extends \Exception
{
    /**
     * The setting errors
     */
    protected array $settingErrors = [];

    /**
     * The accepted error keys
     */
    protected array $acceptedErrorKeys = [
        'key',
        'message',
    ];

    /**
     * Set the data for the exception. Multiple address fields can contain an
     * error so each entry in the array should contain the key and the type of
     * the error.
     * @throws \Exception Should be uncaught to know we're doing it wrong
     */
    public function setErrors(array $settingErrors): SettingsException
    {
        foreach ($settingErrors as $fields) {
            foreach ($fields as $key => $errorData) {
                if (!empty($this->acceptedErrorKeys) && !in_array($key, $this->acceptedErrorKeys)) {
                    throw new \Exception('The key ' . esc_html($key) . ' is not accepted in the data array.');
                }
            }
        }

        $this->settingErrors = $settingErrors;
        return $this;
    }

    /**
     * Get the setting errors from the exception
     */
    public function getErrors(): array
    {
        return $this->settingErrors;
    }
}
