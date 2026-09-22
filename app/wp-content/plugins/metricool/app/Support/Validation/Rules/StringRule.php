<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

class StringRule extends AbstractRule
{
    /**
     * Checks if the field contains a string.
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        if (!is_string($value)) {
            $this->fail(sprintf(
                /* translators: %s is the field name */
                __('%s must be a string.', 'metricool'),
                $field
            ));
        }
    }
}
