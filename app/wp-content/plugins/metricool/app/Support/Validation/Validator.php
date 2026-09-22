<?php

declare(strict_types=1);

namespace Metricool\Support\Validation;

use Metricool\Exceptions\ValidationException;
use Metricool\Support\Validation\Rules\AbstractRule;
use Metricool\Support\Validation\Exceptions\RuleFailedException;

/**
 * Laravel styled validator for validating request data. Each rule is a class
 * in the Rules namespace, resolved from the rule string by the
 * {@see RuleFactory}.
 *
 * Usage:
 *
 *     // Throws a ValidationException on failure, returns the validated data on success
 *     $validated = Validator::validate($request->get_params(), [
 *         'email' => 'required|email',
 *         'password' => 'required|string|min:8',
 *         'terms' => 'accepted',
 *         'marketing' => 'boolean',
 *     ]);
 *
 *     // Or inspect the result manually
 *     $validator = Validator::make($request->get_params(), ['email' => 'required|email']);
 *     if ($validator->fails()) {
 *         $errors = $validator->errors();
 *     }
 *
 * Rules may also be given as {@see AbstractRule} instances or class names,
 * e.g. ['email' => ['required', new EmailRule()]] or
 * ['email' => ['required', EmailRule::class]].
 *
 * Supported rules: required, requiredIf:otherField,value, email, url, string,
 * boolean, accepted, numeric, integer, array, min:x, max:x, in:a,b,c,
 * confirm:otherField
 *
 * The confirm rule checks that the field matches the value of another field,
 * e.g. "passwordConfirmation" => "confirm:password". When no field is given
 * it falls back to "{field}_confirmation".
 */
class Validator
{
    private array $data;
    private array $rules;
    private ?array $errors = null;

    private function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    /**
     * Create a new validator instance for the given data and rules.
     */
    public static function make(array $data, array $rules): self
    {
        return new self($data, $rules);
    }

    /**
     * Validate the given data against the rules and return the validated data.
     * @throws ValidationException when validation fails
     */
    public static function validate(array $data, array $rules): array
    {
        return self::make($data, $rules)->validated();
    }

    /**
     * Check if the validation passes.
     */
    public function passes(): bool
    {
        return empty($this->errors());
    }

    /**
     * Check if the validation fails.
     */
    public function fails(): bool
    {
        return !$this->passes();
    }

    /**
     * Get the validation errors, keyed by field name.
     */
    public function errors(): array
    {
        return $this->errors ??= $this->collectErrors();
    }

    /**
     * Get the validated data, containing only the fields that have rules.
     * @throws ValidationException when validation fails
     */
    public function validated(): array
    {
        if ($this->fails()) {
            // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Errors are returned as JSON
            throw ValidationException::withErrors($this->errors());
        }

        $validated = [];
        foreach (array_keys($this->rules) as $field) {
            if (array_key_exists($field, $this->data)) {
                $validated[$field] = $this->data[$field];
            }
        }

        return $validated;
    }

    /**
     * Run the validation and return the errors, keyed by field name.
     */
    private function collectErrors(): array
    {
        $errors = [];

        foreach ($this->rules as $field => $rules) {
            $fieldErrors = $this->validateField($field, $this->parseRules($rules));

            if (!empty($fieldErrors)) {
                $errors[$field] = $fieldErrors;
            }
        }

        return $errors;
    }

    /**
     * Normalize a rule definition into an array of {@see AbstractRule}
     * instances. Rule strings are resolved with the {@see RuleFactory}.
     * @param string|array $rules
     * @return AbstractRule[]
     */
    private function parseRules($rules): array
    {
        $rules = is_string($rules) ? explode('|', $rules) : (array) $rules;

        return array_map(function ($rule) {
            return $rule instanceof AbstractRule ? $rule : RuleFactory::createFromConfig($rule);
        }, $rules);
    }

    /**
     * Validate a single field against its rules. Required rules run first and stops
     * the validation of the field when they fail.
     *
     * Optional rules are run after the required rules and only when the field is not empty.
     * This prevents seeing redundant errors for a single field, for example:
     * this field is required and this field is too short.
     *
     * @param AbstractRule[] $rules
     * @return string[] the error messages for the field
     */
    private function validateField(string $field, array $rules): array
    {
        $value = $this->data[$field] ?? null;
        $optionalRules = [];
        $errors = [];

        foreach ($rules as $rule) {
            if (!$rule->isRequired()) {
                $optionalRules[] = $rule;
                continue;
            }

            $error = $this->applyRule($field, $value, $rule);

            if ($error !== null) {
                return [$error];
            }
        }

        if (!self::isEmptyValue($value)) {
            foreach ($optionalRules as $rule) {
                $error = $this->applyRule($field, $value, $rule);

                if ($error !== null) {
                    $errors[] = $error;
                }
            }
        }

        return $errors;
    }

    /**
     * Apply a single rule to a field value.
     * @param mixed $value
     * @return string|null the error message when the rule fails, null when it passes
     */
    private function applyRule(string $field, $value, AbstractRule $rule): ?string
    {
        try {
            $rule->validate($field, $value, $this->data);
        } catch (RuleFailedException $e) {
            return $e->getMessage();
        }

        return null;
    }

    /**
     * Determine if a value counts as empty. Booleans are never considered
     * empty.
     * @param mixed $value
     */
    public static function isEmptyValue($value): bool
    {
        return $value === null
            || (is_string($value) && trim($value) === '')
            || (is_array($value) && empty($value));
    }
}
