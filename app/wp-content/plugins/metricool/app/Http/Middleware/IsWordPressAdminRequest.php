<?php

declare(strict_types=1);

namespace Metricool\Http\Middleware;

use Metricool\Interfaces\MiddlewareInterface;
use Metricool\Traits\HasRestAccess;

/**
 * Checks if the current request is made from the WordPress admin. If not, it returns a 401 Unauthorized response.
 */
class IsWordPressAdminRequest implements MiddlewareInterface
{
    use HasRestAccess;

    public function handle(\WP_REST_Request $request, callable $next)
    {
        if (!is_admin()) {
            return $this->sendHttpErrorResponse(
                __('Unauthorized. Please log in into the WordPress admin.', 'metricool'),
                null,
                401
            );
        }

        return $next($request);
    }
}
