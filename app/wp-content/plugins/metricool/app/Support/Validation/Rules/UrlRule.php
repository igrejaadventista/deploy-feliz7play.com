<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

class UrlRule extends AbstractRule
{
    /**
     * Checks if the field contains a valid URL.
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        if (!is_string($value) || filter_var($value, FILTER_VALIDATE_URL) === false) {
            $this->fail(sprintf(
                /* translators: %s is the field name */
                __('%s must be a valid URL.', 'metricool'),
                $field
            ));
        }
    }
}
