<?php

declare(strict_types=1);

namespace Metricool\Http\Endpoints;

use Exception;
use Metricool\Http\Endpoints\Responses\RealtimeResponse;
use Metricool\Http\Metricool\MetricoolApi;
use Metricool\Interfaces\SingleEndpointInterface;
use Metricool\Services\DashboardService;
use Metricool\Services\RealtimeService;
use Metricool\Traits\HasAllowlistControl;
use Metricool\Traits\HasRestAccess;

class RealtimeEndpoint implements SingleEndpointInterface
{
    use HasRestAccess;
    use HasAllowlistControl;

    public const ROUTE = 'realtime';

    public RealtimeService $service;
    public MetricoolApi $metricoolApi;
    public DashboardService $dashboard;

    public function __construct(RealtimeService $service, MetricoolApi $metricoolApi, DashboardService $dashboard)
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
     * Build the specific Realtime response for the endpoint. This is mainly
     * used in the plugin Dashboard. Building it server side prevents client-side complexity.
     *
     *     GET /wp-json/metricool/v1/realtime
     */
    public function callback(\WP_REST_Request $request): \WP_REST_Response
    {
        try {
            $response = $this->buildResponse($request);
        } catch (Exception $e) {
            return $this->sendHttpErrorResponse(__('Failed to load Realtime data', 'metricool'), $e->getMessage(), $e->getCode());
        }

        return $this->sendHttpResponse($response);
    }

    /**
     * Build the specific Analytics response for the endpoint. This is mainly
     * used in the plugin Dashboard to reflect non-realtime statistics.
     * Building it server side prevents client-side complexity.
     * @throws Exception
     */
    private function buildResponse(\WP_REST_Request $request): array
    {
        $realtimeModule = $this->metricoolApi->realtime();

        // Get our data
        $sessions = $realtimeModule->sessions()->get();
        if (!isset($sessions['timeline'])) {
            throw new Exception('Session data is missing');
        }

        $values = $realtimeModule->current()->get();
        if (!isset($values['activeVisits'])) {
            throw new Exception('Visitors data is missing');
        }

        // Add the pageViews to the timeline and totals
        $this->service->addMetric('pageViews', __('Page views', 'metricool'), $sessions['timeline']);
        // Add visitors just to the totals
        $this->service->addTotals('visitors', __('Visitors', 'metricool'), $values['activeVisits']);

        $response = new RealtimeResponse();
        $response->setTotals($this->service->getTotals());
        $response->setTimelineData($this->service->getTimelineData());

        return $response->body();
    }
}
