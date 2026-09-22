<?php

declare(strict_types=1);

namespace Metricool\Http\Endpoints;

use Metricool\Vendor\GuzzleHttp\Exception\GuzzleException;
use Metricool\Http\Endpoints\Responses\ConnectedNetworksResponse;
use Metricool\Http\Metricool\MetricoolApi;
use Metricool\Interfaces\SingleEndpointInterface;
use Metricool\Services\DashboardService;
use Metricool\Traits\HasAllowlistControl;
use Metricool\Traits\HasRestAccess;

class ConnectedNetworksEndpoint implements SingleEndpointInterface
{
    use HasRestAccess;
    use HasAllowlistControl;

    public const ROUTE = 'connected_networks';

    public MetricoolApi $metricoolApi;
    public DashboardService $dashboard;

    public function __construct(MetricoolApi $metricoolApi, DashboardService $dashboard)
    {
        $this->metricoolApi = $metricoolApi;
        $this->dashboard = $dashboard;
    }

    /**
     * @inheritDoc
     */
    public function registerRoute(): string
    {
        return self::ROUTE;
    }

    /**
     * Only enable this endpoint when onboarding is completed
     */
    public function enabled(): bool
    {
        return $this->dashboard->isOnboardingCompleted();
    }

    /**
     * @inheritDoc
     */
    public function registerArguments(): array
    {
        return [
            'methods' => \WP_REST_Server::READABLE,
            'callback' => [$this, 'callback'],
            'middleware' => ['metricool:auth', 'metricool:blog_id'],
        ];
    }

    /**
     * Return the brands related to the user
     *
     *     GET /wp-json/metricool/v1/connected_networks
     */
    public function callback(\WP_REST_Request $request): \WP_REST_Response
    {
        try {
            $response = $this->buildResponse($request);
        } catch (\Exception $e) {
            return $this->sendHttpErrorResponse(__('Failed to load brands data', 'metricool'), $e->getMessage(), $e->getCode());
        }

        return $this->sendHttpResponse($response);
    }

    /**
     * Build the specific ConnectedNetworksResponse response for the endpoint.
     * This response returns just the brand names that are connected to the user.
     * Filtering it server side prevents client-side complexity.
     * @throws GuzzleException
     */
    public function buildResponse(\WP_REST_Request $request): array
    {
        $connectedBrand = $this->metricoolApi->connectedBrands()->get();
        $response = new ConnectedNetworksResponse($connectedBrand);

        return $response->body();
    }
}
