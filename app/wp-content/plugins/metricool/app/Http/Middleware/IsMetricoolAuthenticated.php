<?php

declare(strict_types=1);

namespace Metricool\Http\Middleware;

use Metricool\Http\Metricool\MetricoolClient;
use Metricool\Interfaces\MiddlewareInterface;
use Metricool\Traits\HasRestAccess;

/**
 * Checks if the client is authenticated with Metricool API.
 */
class IsMetricoolAuthenticated implements MiddlewareInterface
{
    use HasRestAccess;

    private MetricoolClient $client;

    public function __construct(MetricoolClient $client)
    {
        $this->client = $client;
    }

    public function handle(\WP_REST_Request $request, callable $next)
    {
        if (!$this->client->hasAuthentication()) {
            return $this->sendHttpErrorResponse(
                __('Unauthorized. Please log-in into the Metricool plugin.', 'metricool'),
                null,
                401
            );
        }

        return $next($request);
    }
}
