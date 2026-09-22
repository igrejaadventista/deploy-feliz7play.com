<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

class BooleanRule extends AbstractRule
{
    /**
     * Checks if the field contains a boolean-like value.
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        if (!in_array($value, [true, false, 0, 1, '0', '1', 'true', 'false'], true)) {
            $this->fail(sprintf(
                /* translators: %s is the field name */
                __('%s must be true or false.', 'metricool'),
                $field
            ));
        }
    }
}
