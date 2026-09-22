<?php

namespace Metricool\Http\RSPAL;

use Metricool\Vendor\GuzzleHttp\Client;
use Metricool\Vendor\GuzzleHttp\Exception\GuzzleException;
use Metricool\Vendor\GuzzleHttp\Exception\RequestException;
use Metricool\Exceptions\RestDataException;
use Metricool\Http\Metricool\MetricoolClient;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class RspalApiClient
{
    /**
     * The option name for storing the InstallationID.
     */
    private const INSTALLATION_ID_OPTION = '_metricool_rspal_installation_id';

    /**
     * The default value for the InstallationID when it is not set.
     */
    private const UNKNOWN_INSTALLATION_ID = 'unknown';

    /**
     * The Guzzle HTTP client for making API requests
     */
    protected Client $client;

    /**
     * The base endpoint URL for the brand API
     */
    private string $baseEndpoint;

    /**
     * The default headers to send with each request
     */
    private array $headers = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    private EnvironmentConfig $env;
    private MetricoolClient $metricool;

    public function __construct(EnvironmentConfig $env, MetricoolClient $metricool)
    {
        $this->env = $env;
        $this->metricool = $metricool;

        $this->baseEndpoint = $this->env->getUrl('metricool.rsp_auth_url');
        $this->client = $this->client();
    }

    /**
     * Sign Up Endpoint
     *
     * @throws RestDataException
     */
    public function signUp(array $data, array $headers = []): RspalApiResponse
    {
        $response =  $this->request('v2/integrations/wp-plugin/users/sign-ups', [
            'json' => $data,
            'headers' => $this->headers($headers)
        ], 'post');

        if ($response->getStatusCode() == 400) {
            throw new RestDataException('Email or password error', 422);
        }

        // Check if the response contains the required fields
        if (empty($response->data->accessToken) || empty($response->data->refreshToken)) {
            throw new RestDataException('Signup response is missing required fields', 500);
        }

        return $response;
    }

    /**
     * Make a request to the RSPAL API.
     *
     * @throws RestDataException
     */
    private function request(string $path, array $params = [], string $method = 'get'): RspalApiResponse
    {
        try {
            // Request and store installationId if needed before any request
            if (!$this->hasInstallationId()) {
                $installation = $this->requestInstallation();
                $this->setInstallationId($installation->data->uuid);
            }

            $response = $this->client->request(strtoupper($method), $this->uri($path), $params);
        } catch (RequestException $e) {
            throw (new RestDataException($e->getMessage()))
                ->setResponseCode($e->getResponse()->getStatusCode());
        } catch (GuzzleException $e) {
            throw new RestDataException($e->getMessage());
        }

        return RspalApiResponse::fromResponse($response);
    }

    /**
     * Request a new InstallationID from the RSPAL API.
     *
     * @throws GuzzleException
     * @throws RestDataException
     */
    private function requestInstallation(): RspalApiResponse
    {
        $response = $this->client->request('POST', $this->uri('installation/create'), [
            'headers' => $this->headers()
        ]);

        return RspalApiResponse::fromResponse($response);
    }

    /**
     * Create a new Guzzle HTTP client instance.
     */
    private function client(): Client
    {
        return new Client([
            'http_errors' => false,
            'verify' => false,
        ]);
    }

    /**
     * Get any headers that should be sent with the request.
     */
    private function headers(array $headers = []): array
    {
        $headers['User-Agent'] = $this->metricool->getRequestUserAgent();

        return array_merge($this->headers, $this->rspalHeaders(), $headers);
    }

    protected function rspalHeaders(): array
    {
        $installationId = $this->getInstallationId();

        $rspalHeaders = [
            'RSPAL-PluginName' => $this->env->getString('plugin.name'),
            'RSPAL-PluginVersion' => $this->env->getString('plugin.version'),
            'RSPAL-PluginPath' => $this->getPluginPathHeader(),
            'RSPAL-Origin' => trailingslashit(site_url()),
            'RSPAL-InstallationId' => $installationId,
        ];

        $rspalHeaders['RSPAL-Signature'] = $this->getInstallationSignature($rspalHeaders, $installationId);

        return $rspalHeaders;
    }

    /**
     * Get the plugin path relative to the WordPress root directory.
     */
    private function getPluginPathHeader(): string
    {
        $pluginFullPath = wp_normalize_path(realpath($this->env->getString('plugin.path')));
        $wpRoot = wp_normalize_path(realpath(ABSPATH));

        return str_replace($wpRoot, '', $pluginFullPath);
    }

    /**
     * Get the full URI for the given path.
     */
    private function uri(string $path): string
    {
        return rtrim($this->baseEndpoint, '/') . '/' . ltrim($path, '/');
    }

    /**
     * Generate the installation signature.
     */
    private function getInstallationSignature(array $format, string $id): string
    {
        return hash_hmac('sha256', json_encode($format), $id);
    }

    /**
     * Stores the InstallationID in wp_options
     */
    private function setInstallationId(string $installationId): void
    {
        $installation = update_option(self::INSTALLATION_ID_OPTION, $installationId, false);

        if (!$installation) {
            throw new \RuntimeException('Failed to store InstallationID');
        }
    }

    /**
     * Check if the InstallationID has already been stored
     */
    private function hasInstallationId(): bool
    {
        return $this->getInstallationId() !== self::UNKNOWN_INSTALLATION_ID;
    }

    /**
     * Return the installationId from wp_options
     */
    private function getInstallationId(): string
    {
        return get_option(self::INSTALLATION_ID_OPTION, self::UNKNOWN_INSTALLATION_ID);
    }
}
