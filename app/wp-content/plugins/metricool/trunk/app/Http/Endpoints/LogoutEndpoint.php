<?php

declare(strict_types=1);

namespace Metricool\Http\Endpoints;

use Metricool\Http\Metricool\MetricoolApi;
use Metricool\Interfaces\SingleEndpointInterface;
use Metricool\Services\DashboardService;
use Metricool\Traits\HasAllowlistControl;
use Metricool\Traits\HasRestAccess;

class LogoutEndpoint implements SingleEndpointInterface
{
    use HasRestAccess;
    use HasAllowlistControl;

    public const ROUTE = 'logout';

    private MetricoolApi $metricoolApi;
    private DashboardService $dashboard;

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
     * @inheritDoc
     */
    public function enabled(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function registerArguments(): array
    {
        return [
            'methods' => \WP_REST_Server::EDITABLE,
            'callback' => [$this, 'callback'],
            'middleware' => ['metricool:auth'],
        ];
    }

    /**
     * Log the user out and redirect to the dashboard
     *
     *     POST /wp-json/metricool/v1/logout
     */
    public function callback(\WP_REST_Request $request): \WP_REST_Response
    {
        $this->metricoolApi->logout();

        return $this->sendHttpResponse([
            'state' => $this->dashboard->state(),
            'mode' => $this->dashboard->mode(),
        ]);
    }
}
