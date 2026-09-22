<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings\Storage;

/**
 * This storage uses the wp_options to store and retrieve the UserSettings
 */
class OptionsStorage extends AbstractStorage
{
    private string $prefix;

    public function __construct(string $name, array $config)
    {
        if (!isset($config['prefix'])) {
            throw new \InvalidArgumentException('Prefix is required for OptionsStorage: ' . esc_html($name));
        }

        parent::__construct($name);

        $this->prefix = $config['prefix'];
        $this->casing = $config['casing'] ?? 'snakeCase';
    }

    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        return get_option($this->prefix . $this->convertCase($key)) ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getMultiple(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($this->convertCase($key));
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function store(string $key, $value): void
    {
        update_option($this->prefix . $this->convertCase($key), $value, false);
    }

    /**
     * @inheritDoc
     */
    public function storeMultiple(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->store($key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function save(): void
    {
        if (empty($this->settings)) {
            return;
        }

        $this->storeMultiple($this->settings);
    }
}
