<?php

declare(strict_types=1);

namespace Metricool\Interfaces;

interface MiddlewareInterface
{
    /**
     * Handle an incoming REST request as part of the middleware pipeline.
     *
     * Middleware are composed into an "onion" pipeline by
     * {@see \Metricool\Managers\EndpointManager::callbackMiddleware()}, where each
     * layer wraps the next one and the endpoint callback sits at the core.
     *
     * Continue the request with: `return $next($request);`
     *
     * Short-circuit: return a `\WP_REST_Response` directly,
     *    e.g. a 403 when an authorization check fails. Neither the remaining
     *    middleware nor the endpoint callback will run.
     *
     * Thrown exceptions are caught by the pipeline wrapper and converted into a
     * 500 `\WP_REST_Response` containing the exception message.
     *
     * @param \WP_REST_Request $request The incoming REST request.
     * @param callable(\WP_REST_Request): mixed $next The next middleware in the pipeline
     * @return mixed `$next($request)` or `\WP_REST_Response` when short-circuiting.
     */
    public function handle(\WP_REST_Request $request, callable $next);
}
