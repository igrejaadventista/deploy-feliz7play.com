<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings\Factories;

use Metricool\Features\UserSettings\Fields\Field;
use Metricool\Features\UserSettings\Storage\AbstractStorage;

class FieldFactory
{
    private const FIELDS_NAMESPACE = '\\Metricool\\Features\\UserSettings\\Fields\\';

    /**
     * Creates a field from the user_settings configuration
     * @see config/user_settings.php
     */
    public static function createFromConfig(string $name, array $config, ?AbstractStorage $storage = null): Field
    {
        $fieldClassName = ($config['field'] ?? 'Field');
        $fieldClass = self::FIELDS_NAMESPACE . $fieldClassName;

        if (!class_exists($fieldClass)) {
            throw new \InvalidArgumentException('Field "' . esc_html($fieldClass) . '" not found');
        }

        $field = new $fieldClass($name);
        $field->applyConfig($config);

        if ($storage !== null) {
            $field->setStorage($storage);
        }

        return $field;
    }
}
