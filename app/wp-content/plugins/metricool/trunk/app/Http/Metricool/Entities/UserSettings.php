<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool\Entities;

use Metricool\Http\Metricool\Exceptions\ApiException;
use Metricool\Http\Metricool\MetricoolClient;

class UserSettings
{
    protected MetricoolClient $client;
    private string $endpoint = 'v2/settings/users/';

    public function __construct(MetricoolClient $client)
    {
        $this->client = $client;
        $this->endpoint = $this->endpoint . $client->getUserId();
    }

    /**
     * @throws ApiException
     */
    public function get(): array
    {
        $response = $this->client->get($this->endpoint);
        return ($response['data'] ?? []);
    }

    /**
     * @throws ApiException
     */
    public function patch(array $data): array
    {
        $response = $this->client->patch(
            $this->getFieldPatchEndpoint($data),
            $data
        );

        return ($response['data'] ?? []);
    }

    /**
     * This method will return the endpoint to be used for the patch request.
     * It will append the fields as query parameters to the endpoint.
     *
     * @example /v2/settings/users/123?fields=name&fields=language
     *
     * @internal We cannot use {@see add_query_arg()} here because it does not
     * support multiple query parameters with the same name:
     * {@see https://core.trac.wordpress.org/ticket/51552}
     */
    private function getFieldPatchEndpoint(array $payload): string
    {
        if (empty($this->endpoint)) {
            throw new \InvalidArgumentException('Endpoint cannot be empty');
        }

        $filters = array_keys($payload);
        $queryString = implode('&', array_map(function ($value) {
            return 'fields=' . urlencode($value);
        }, $filters));

        if (empty($queryString)) {
            return $this->endpoint;
        }

        return $this->endpoint . '?' . $queryString;
    }
}
