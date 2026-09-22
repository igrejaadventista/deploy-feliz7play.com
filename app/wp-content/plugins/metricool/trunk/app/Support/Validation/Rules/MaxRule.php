<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

class MaxRule extends AbstractRule
{
    /**
     * Checks if the size of the field is not greater than the given maximum.
     * Uses the numeric value for numbers, the length for strings and the
     * count for arrays.
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        $max = (float) ($this->parameters[0] ?? 0);

        if ($this->sizeOf($value) > $max) {
            $this->fail(sprintf(
                /* translators: %1$s is the field name, %2$s is the maximum size */
                __('%1$s must not be greater than %2$s.', 'metricool'),
                $field,
                $this->parameters[0] ?? '0'
            ));
        }
    }
}
