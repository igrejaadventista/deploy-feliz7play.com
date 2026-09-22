<?php

declare(strict_types=1);

namespace Metricool\Http\Endpoints;

use Metricool\Http\Endpoints\Responses\DistributionResponse;
use Metricool\Http\Endpoints\Responses\Statistics\CountriesResponse;
use Metricool\Http\Endpoints\Responses\Statistics\RefererResponse;
use Metricool\Http\Metricool\DTOs\DistributionDTO;
use Metricool\Http\Metricool\MetricoolApi;
use Metricool\Interfaces\SingleEndpointInterface;
use Metricool\Services\DashboardService;
use Metricool\Support\Helpers\Collection;
use Metricool\Support\Validation\Validator;
use Metricool\Traits\HasAllowlistControl;
use Metricool\Traits\HasRestAccess;

class DistributionEndpoint implements SingleEndpointInterface
{
    use HasRestAccess;
    use HasAllowlistControl;

    public const ROUTE = 'distribution';

    private const METRICS_RESPONSE_MAPPER = [
        'countries' => CountriesResponse::class,
        'referers' => RefererResponse::class,
    ];

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
        return self::ROUTE . '/(?P<metric>[^/]+)';
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
     *     GET /wp-json/metricool/v1/distribution/countries?filters[start]=20250618&filters[end]=20250718&filters[country]=nl
     */
    public function callback(\WP_REST_Request $request): \WP_REST_Response
    {
        $validated = Validator::validate($request->get_params(), [
            'metric' => 'required|string|in:countries,referers',
            'filters' => 'array',
        ]);

        try {
            $response = $this->buildResponse($validated);
        } catch (\Exception $e) {
            return $this->sendHttpErrorResponse(__('Failed to load Analytics data', 'metricool'), $e->getMessage(), $e->getCode());
        }

        return $this->sendHttpResponse($response);
    }

    /**
     * Build the specific Analytics response for the endpoint. This is mainly
     * used in the plugin Dashboard to reflect non-realtime statistics.
     * Building it server side prevents client-side complexity.
     *
     * @throws \Exception
     */
    private function buildResponse(array $validated): array
    {
        $metric = $validated['metric'];
        $requestFilters = $validated['filters'] ?? [];

        // Load the statistics
        $statistics = $this->getStatisticsForMetric($metric, $requestFilters);

        // Find the associated response object for the metric
        $response = $this->createResponseObjectFromMetric($metric, $statistics);

        return $response->body();
    }

    /**
     * Loads the results from the Metricool API
     * @return Collection|DistributionDTO[]
     */
    protected function getStatisticsForMetric(string $metric, array $filters): Collection
    {
        $statisticsModule = $this->metricoolApi->statistics();

        // Load the results
        $metricModule = $statisticsModule->$metric();
        if (!empty($filters)) {
            $metricModule->filter($filters);
        }

        return $metricModule->get();
    }

    /**
     * Find the response that matches the requested metric or throw an exception.
     * Each metric has its own specific serialisation of the results and chartData
     * @param Collection|DistributionDTO[] $statistics
     */
    protected function createResponseObjectFromMetric(string $metric, Collection $statistics): DistributionResponse
    {
        if (!array_key_exists($metric, self::METRICS_RESPONSE_MAPPER)) {
            throw new \InvalidArgumentException(esc_html("Metric $metric is not accepted by this endpoint"));
        }

        $response = self::METRICS_RESPONSE_MAPPER[$metric];

        return new $response($statistics);
    }
}
