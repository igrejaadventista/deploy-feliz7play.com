<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

class AcceptedRule extends AbstractRule
{
    /**
     * Checks if the field is accepted, e.g. a checked terms checkbox.
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        if (!in_array($value, [true, 1, '1', 'true', 'yes', 'on'], true)) {
            $this->fail(sprintf(
                /* translators: %s is the field name */
                __('%s must be accepted.', 'metricool'),
                $field
            ));
        }
    }

    /**
     * @inheritDoc
     */
    public function isRequired(): bool
    {
        return true;
    }
}
