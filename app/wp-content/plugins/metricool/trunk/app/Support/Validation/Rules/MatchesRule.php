<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

class MatchesRule extends AbstractRule
{
    /**
     * Checks if the field matches the value of another field, e.g.
     * "passwordConfirmation" => "matches:password". When no field is given it
     * falls back to "{field}_confirmation".
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        $otherField = $this->parameters[0] ?? $field . '_confirmation';

        if (!array_key_exists($otherField, $data) || $data[$otherField] !== $value) {
            $this->fail(sprintf(
                /* translators: %1$s and %2$s are field names */
                __('%1$s must match %2$s.', 'metricool'),
                $field,
                $otherField
            ));
        }
    }
}
