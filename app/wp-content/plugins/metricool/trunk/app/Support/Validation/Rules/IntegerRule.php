<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

class IntegerRule extends AbstractRule
{
    /**
     * Checks if the field contains an integer value.
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            $this->fail(sprintf(
                /* translators: %s is the field name */
                __('%s must be an integer.', 'metricool'),
                $field
            ));
        }
    }
}
