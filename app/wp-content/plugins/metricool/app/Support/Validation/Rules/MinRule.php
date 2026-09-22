<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

class MinRule extends AbstractRule
{
    /**
     * Checks if the size of the field is at least the given minimum. Uses the
     * numeric value for numbers, the length for strings and the count for
     * arrays.
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        $min = (float) ($this->parameters[0] ?? 0);

        if ($this->sizeOf($value) < $min) {
            $this->fail(sprintf(
                /* translators: %1$s is the field name, %2$s is the minimum size */
                __('%1$s must be at least %2$s.', 'metricool'),
                $field,
                $this->parameters[0] ?? '0'
            ));
        }
    }
}
