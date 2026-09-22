<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

/**
 * Makes a field required when another field has a given value, e.g.
 * "requiredIf:sendToAlternativeEmail,true".
 */
class RequiredIfRule extends AbstractRule
{
    /**
     * Checks if the required param matches the required value in the data and
     * then validates the field with the {@see RequiredRule}
     */
    public function validate(string $field, $value, array $data): void
    {
        if ($this->shouldValidate($data)) {
            (new RequiredRule())->validate($field, $value, $data);
        }
    }

    /**
     * @inheritDoc
     */
    public function isRequired(): bool
    {
        return true;
    }

    /**
     * Checks if the required param matches the required value in the data.
     * Uses a loose comparison so the string parameter "true" also matches a
     * boolean request value.
     */
    protected function shouldValidate(array $data): bool
    {
        $requiredParam = $this->parameters[0] ?? '';
        $requiredValue = $this->parameters[1] ?? null;

        return ($data[$requiredParam] ?? null) == $requiredValue;
    }
}
