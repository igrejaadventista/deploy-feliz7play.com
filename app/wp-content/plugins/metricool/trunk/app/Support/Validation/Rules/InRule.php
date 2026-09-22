<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

class InRule extends AbstractRule
{
    /**
     * Checks if the field value is one of the given parameters,
     * e.g. "in:countries,referers".
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        if (!in_array((string) $value, $this->parameters, true)) {
            $this->fail(sprintf(
                /* translators: %s is the field name */
                __('%s is invalid.', 'metricool'),
                $field
            ));
        }
    }
}
