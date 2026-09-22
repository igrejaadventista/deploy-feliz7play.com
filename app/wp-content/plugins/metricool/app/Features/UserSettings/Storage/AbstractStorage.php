<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings\Storage;

use Metricool\Support\Utility\StringUtility;

/**
 * Storage is responsible for storing and retrieving settings
 */
abstract class AbstractStorage
{
    public string $name;
    protected string $casing;

    /**
     * This property is used to (temporarily) store settings before saving them.
     */
    protected array $settings = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Retrieve a value from storage
     * @return mixed
     * @throws \Exception when the value could not be retrieved
     */
    abstract public function get(string $key);

    /**
     * Retrieve multiple values from storage
     * @throws \Exception when a value could not be retrieved
     */
    abstract public function getMultiple(array $keys): array;

    /**
     * Store a setting
     * @param mixed $value
     * @throws \Exception when the value could not be stored
     */
    abstract public function store(string $key, $value): void;

    /**
     * Store multiple settings
     * @throws \Exception when one of the values could not be stored
     */
    abstract public function storeMultiple(array $settings): void;

    /**
     * Save the {@see settings} property to storage. The child class determines
     * how the settings are saved. Return silently if there are no settings to
     * save.
     * @throws \Exception when the settings could not be saved
     */
    abstract public function save(): void;

    /**
     * Use this method to set a value to the internal {@see settings} property
     * that will be saved later when {@see save()} is called.
     * @param mixed $value
     * @throws \InvalidArgumentException when the casing is unknown
     */
    public function set(string $key, $value): void
    {
        $this->settings[$this->convertCase($key)] = $value;
    }

    /**
     * Use this method to set multiple values to the internal {@see settings}
     * property that will be saved later when {@see save()} is called.
     * @throws \InvalidArgumentException when the casing is unknown
     */
    public function setMultiple(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Converts the casing to storage casing
     * @throws \InvalidArgumentException when the casing is unknown
     */
    protected function convertCase(string $key): string
    {
        switch ($this->casing) {
            case 'pascalCase':
                return StringUtility::snakeToPascalCase($key);
            case 'camelCase':
                return StringUtility::snakeToCamelCase($key);
            case 'snakeCase':
                return StringUtility::camelToSnakeCase($key);
            case '':
                return $key;
            default:
                throw new \InvalidArgumentException('Unknown casing type: ' . esc_html($this->casing) . ' for storage: ' . esc_html($this->name) . '.');
        }
    }
}
