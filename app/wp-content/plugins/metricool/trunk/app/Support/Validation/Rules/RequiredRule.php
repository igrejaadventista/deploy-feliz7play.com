<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

use Metricool\Support\Validation\Validator;

class RequiredRule extends AbstractRule
{
    /**
     * Checks if the field is present and not empty.
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        if (Validator::isEmptyValue($value)) {
            $this->fail(sprintf(
                /* translators: %s is the field name */
                __('%s is required.', 'metricool'),
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
