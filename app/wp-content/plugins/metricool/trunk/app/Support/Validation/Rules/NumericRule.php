<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

class NumericRule extends AbstractRule
{
    /**
     * Checks if the field contains a numeric value.
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        if (!is_numeric($value)) {
            $this->fail(sprintf(
                /* translators: %s is the field name */
                __('%s must be a number.', 'metricool'),
                $field
            ));
        }
    }
}
