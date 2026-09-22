<?php

declare(strict_types=1);

namespace Metricool\Http\Endpoints;

use Metricool\Http\Metricool\MetricoolApi;
use Metricool\Interfaces\SingleEndpointInterface;
use Metricool\Traits\HasAllowlistControl;
use Metricool\Traits\HasRestAccess;

class ConnectedBrandsEndpoint implements SingleEndpointInterface
{
    use HasRestAccess;
    use HasAllowlistControl;

    public const ROUTE = 'connected_brands';

    public MetricoolApi $metricoolApi;

    public function __construct(MetricoolApi $metricoolApi)
    {
        $this->metricoolApi = $metricoolApi;
    }

    /**
     * @inheritDoc
     */
    public function enabled(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function registerRoute(): string
    {
        return self::ROUTE;
    }

    /**
     * @inheritDoc
     */
    public function registerArguments(): array
    {
        return [
            'methods' => \WP_REST_Server::READABLE,
            'callback' => [$this, 'callback'],
            'middleware' => ['metricool:auth'],
        ];
    }

    /**
     * Return the brands related to the user
     *
     *     GET /wp-json/metricool/v1/connected_brands
     */
    public function callback(\WP_REST_Request $request): \WP_REST_Response
    {
        try {
            $response = $this->metricoolApi->brands()->all();
        } catch (\Throwable $e) {
            return $this->sendHttpErrorResponse(__('Failed to load brands data', 'metricool'), $e->getMessage(), $e->getCode());
        }

        return $this->sendHttpResponse($response);
    }
}
