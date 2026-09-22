<?php

declare(strict_types=1);

namespace Metricool\Http\Endpoints;

use Metricool\Http\Endpoints\Responses\AnalyticsResponse;
use Metricool\Http\Metricool\MetricoolApi;
use Metricool\Interfaces\SingleEndpointInterface;
use Metricool\Services\AnalyticsService;
use Metricool\Services\DashboardService;
use Metricool\Traits\HasAllowlistControl;
use Metricool\Traits\HasRestAccess;

class AnalyticsEndpoint implements SingleEndpointInterface
{
    use HasRestAccess;
    use HasAllowlistControl;

    public const ROUTE = 'analytics';

    public AnalyticsService $service;
    public MetricoolApi $metricoolApi;
    public DashboardService $dashboard;

    public function __construct(AnalyticsService $service, MetricoolApi $metricoolApi, DashboardService $dashboard)
    {
        $this->service = $service;
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
     * Method will dynamically request the requested statistic. If the metric
     * is filterable and filters are provided, it will apply them before
     * retrieving the data.
     *
     *     GET /wp-json/metricool/v1/analytics?filters[start]=20250618&filters[end]=20250718&metrics[]=pageViews&metrics[]=visits
     */
    public function callback(\WP_REST_Request $request): \WP_REST_Response
    {
        try {
            $response = $this->buildResponse($request);
        } catch (\Exception $e) {
            return $this->sendHttpErrorResponse(__('Failed to load Analytics data', 'metricool'), $e->getMessage(), $e->getCode());
        }

        return $this->sendHttpResponse($response);
    }

    /**
     * Build the specific Analytics response for the endpoint. This is mainly
     * used in the plugin Dashboard to reflect non-realtime statistics.
     * Building it server side prevents client-side complexity.
     */
    private function buildResponse(\WP_REST_Request $request): array
    {
        $statisticsModule = $this->metricoolApi->statistics();
        $requestFilters = ($request->get_param('filters') ?: []);
        $requestMetrics = ($request->get_param('metrics') ?: ['pageViews', 'visits', 'visitors', 'posts', 'comments']);

        $this->service->setRequestFilters($requestFilters);

        if (in_array('pageViews', $requestMetrics)) {
            $this->service->loadMetric('pageViews', __('Page views', 'metricool'), $statisticsModule->pageViews());
        }

        if (in_array('visits', $requestMetrics)) {
            $this->service->loadMetric('visits', __('Visits', 'metricool'), $statisticsModule->visits());
        }

        if (in_array('visitors', $requestMetrics)) {
            $this->service->loadMetric('visitors', __('Visitors', 'metricool'), $statisticsModule->visitors());
        }

        if (in_array('posts', $requestMetrics)) {
            $this->service->loadMetric('posts', __('Posts', 'metricool'), $statisticsModule->posts());
        }

        if (in_array('comments', $requestMetrics)) {
            $this->service->loadMetric('comments', __('Comments', 'metricool'), $statisticsModule->comments());
        }

        $response = new AnalyticsResponse();
        $response->setTotals($this->service->getTotals());
        $response->setTimelineData($this->service->getTimelineData());

        return $response->body();
    }
}
