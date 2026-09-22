<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool\Entities;

use Metricool\Http\Metricool\Exceptions\ApiException;
use Metricool\Http\Metricool\MetricoolClient;

class Brands
{
    protected MetricoolClient $client;
    private string $endpoint = 'v2/settings/brands/';

    public function __construct(MetricoolClient $client)
    {
        $this->client = $client;
    }

    /**
     * Stub method to get all connected brands
     * @throws ApiException
     */
    public function all(): array
    {
        $result = $this->client->get($this->endpoint);

        if (!isset($result['data'])) {
            return [];
        }

        return $result['data'];
    }

    /**
     * Stub method to get brand by id
     * @throws ApiException
     */
    public function get(string $id): array
    {
        // append the blogId parameter to the endpoint, to prevent 403 response
        $url = add_query_arg('blogId', $id, $this->endpoint . $id);
        $result = $this->client->get($url);

        if (!isset($result['data'])) {
            return [];
        }

        return $result['data'];
    }
}
