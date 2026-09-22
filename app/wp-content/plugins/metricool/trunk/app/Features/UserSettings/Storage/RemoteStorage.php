<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings\Storage;

use Metricool\Bootstrap\App;
use Metricool\Http\Metricool\MetricoolApi;

/**
 * This storage uses a client to store and retrieve the UserSettings
 */
class RemoteStorage extends AbstractStorage
{
    protected object $client;
    protected string $method;

    /**
     * This property is used to avoid multiple requests to the remote client.
     * It stores the settings retrieved from the client and any changes made
     * to them before submitting.
     */
    protected array $settings = [];

    public function __construct(string $name, array $config)
    {
        parent::__construct($name);

        $this->client = App::getInstance()->get(MetricoolApi::class)->userSettings();
        $this->method = $config['method'] ?? 'post';
        $this->casing = $config['casing'] ?? '';
    }

    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        if (!empty($this->settings)) {
            $settingsKey = $this->convertCase($key);
            return $this->settings[$settingsKey] ?? null;
        }

        $value = $this->getMultiple([$key]);
        return $value[$key];
    }

    /**
     * @inheritDoc
     */
    public function getMultiple(array $keys): array
    {
        $data = [];

        // Retrieve all values from the client for the first time
        if (empty($this->settings)) {
            $this->settings = $this->client->get();
        }

        // Retrieve the requested values from the response
        foreach ($keys as $key) {
            $data[$key] = $this->settings[$this->convertCase($key)] ?? null;
        }

        // Return the requested values
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function store(string $key, $value): void
    {
        $this->storeMultiple([$key => $value]);
    }

    /**
     * @inheritDoc
     */
    public function storeMultiple(array $settings): void
    {
        // Create the request data
        $requestData = [];
        foreach ($settings as $key => $value) {
            $requestData[$this->convertCase($key)] = $value;
        }

        // Send the request to the client
        $this->client->{$this->method}($requestData);
    }

    /**
     * @inheritDoc
     */
    public function save(): void
    {
        if (empty($this->settings)) {
            return;
        }

        $this->client->{$this->method}($this->settings);
    }
}
