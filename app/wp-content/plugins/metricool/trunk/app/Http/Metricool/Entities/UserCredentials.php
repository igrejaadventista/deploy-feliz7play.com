<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool\Entities;

use Metricool\Http\Metricool\Exceptions\ApiException;
use Metricool\Http\Metricool\MetricoolClient;

class UserCredentials
{
    protected MetricoolClient $client;
    protected string $endpoint;

    public function __construct(MetricoolClient $client)
    {
        $this->client = $client;
        $this->endpoint = 'v2/settings/users/' . $client->getUserId() . '/credentials';
    }

    /**
     * Update the user password
     *
     * @throws ApiException
     */
    public function updatePassword(string $oldPassword, string $newPassword): array
    {
         $this->client->patch($this->endpoint . '?fields=password', [
             'oldPassword' => $oldPassword,
             'password' => $newPassword,
         ]);

        return ['success' => true];
    }
}
