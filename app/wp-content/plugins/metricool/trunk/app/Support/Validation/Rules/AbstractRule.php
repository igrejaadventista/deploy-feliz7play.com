<?php

declare(strict_types=1);

namespace Metricool\Support\Validation\Rules;

use Metricool\Support\Validation\Exceptions\RuleFailedException;

abstract class AbstractRule
{
    /**
     * The parameters of the rule, e.g. "min:8" has the parameter "8".
     * @var string[]
     */
    protected array $parameters;

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Validates the given value according to the rule.
     * @param string $field The name of the field under validation
     * @param mixed $value The value under validation
     * @param array $data All the data under validation, for rules that depend
     * on other fields
     * @throws RuleFailedException when the rule fails
     */
    abstract public function validate(string $field, $value, array $data): void;

    /**
     * Whether the rule is required. Required rules are run by the Validator
     * before all other rules and stop the validation of the field when they
     * fail, e.g. the required rule. Override this method to make a rule
     * required.
     */
    public function isRequired(): bool
    {
        return false;
    }

    /**
     * Fail the rule with the given error message.
     * @throws RuleFailedException
     */
    protected function fail(string $message): void
    {
        // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Messages are collected by the Validator and returned as JSON
        throw new RuleFailedException($message);
    }

    /**
     * Get the size of a value for size based rules. Uses the numeric value
     * for numbers, the length for strings and the count for arrays.
     * @param mixed $value
     */
    protected function sizeOf($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        if (is_array($value)) {
            return (float) count($value);
        }

        return (float) mb_strlen((string) $value);
    }
}
