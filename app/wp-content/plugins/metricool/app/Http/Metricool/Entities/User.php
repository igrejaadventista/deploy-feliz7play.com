<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool\Entities;

use Metricool\Http\Metricool\Exceptions\ApiException;
use Metricool\Http\Metricool\MetricoolClient;

class User
{
    protected MetricoolClient $client;
    protected string $endpoint;

    public function __construct(MetricoolClient $client)
    {
        $this->client = $client;
        $this->endpoint = 'user/settings';
    }

    /**
     * Get the user information
     *
     * @throws ApiException
     */
    public function get(): array
    {
        return $this->client->get($this->endpoint);
    }
}
