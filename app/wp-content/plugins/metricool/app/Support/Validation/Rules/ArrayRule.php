<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

class ArrayRule extends AbstractRule
{
    /**
     * Checks if the field contains an array.
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        if (!is_array($value)) {
            $this->fail(sprintf(
                /* translators: %s is the field name */
                __('%s must be an array.', 'metricool'),
                $field
            ));
        }
    }
}
