<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

class EmailRule extends AbstractRule
{
    /**
     * Checks if the field contains a valid email address. Empty values pass,
     * combine with the required or requiredIf rule when the field must be
     * filled.
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        if (!is_string($value) || !is_email($value)) {
            $this->fail(sprintf(
                /* translators: %s is the field name */
                __('%s must be a valid email address.', 'metricool'),
                $field
            ));
        }
    }
}
