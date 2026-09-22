<?php

declare(strict_types=1);

namespace Metricool\Support\Validation;

use Metricool\Bootstrap\App;
use Metricool\Support\Validation\Rules\AbstractRule;

class RuleFactory
{
    private const RULES_NAMESPACE = '\\Metricool\\Support\\Validation\\Rules\\';

    /**
     * Creates a rule instance from the configuration string.
     *
     * Example rule strings:
     * 'required', 'email', 'min:8', 'in:param1,param2'
     *
     * The name of the rule class will be converted to PascalCase and suffixed
     * with "Rule". The Factory will try to find the class with
     * {@see resolveClass}.
     *
     * Fully qualified class names are also supported, e.g. TimezoneRule::class
     * for rules that live outside the Rules namespace.
     */
    public static function createFromConfig(string $ruleConfig): AbstractRule
    {
        // Support fully qualified class names, e.g. TimezoneRule::class.
        // Resolved through the container so dependencies are autowired.
        if (is_subclass_of($ruleConfig, AbstractRule::class)) {
            /** @var AbstractRule */
            return App::getInstance()->make($ruleConfig, false);
        }

        // Extract the name and parameters from the rule string
        $ruleInfo = self::parseRuleConfig($ruleConfig);

        $ruleClass = static::resolveClass(ucfirst($ruleInfo['className']));

        return new $ruleClass($ruleInfo['params']);
    }

    /**
     * Resolve the fully qualified class name for a rule. Override this method
     * to resolve rules from another namespace first.
     * @throws \InvalidArgumentException when the rule class does not exist
     */
    protected static function resolveClass(string $className): string
    {
        $ruleClass = self::RULES_NAMESPACE . $className;
        if (!class_exists($ruleClass)) {
            throw new \InvalidArgumentException('Validation rule "' . esc_html($ruleClass) . '" not found');
        }

        return $ruleClass;
    }

    /**
     * Parse a rule string into an array with the class name and parameters
     * Example: "in:param1,param2"
     * Becomes: ['className' => 'inRule', 'params' => ['param1', 'param2']]
     */
    protected static function parseRuleConfig(string $rule): array
    {
        $parts = explode(':', $rule, 2);

        return [
            'className' => $parts[0] . 'Rule',
            'params' => (count($parts) > 1) ? explode(',', $parts[1]) : [],
        ];
    }
}
