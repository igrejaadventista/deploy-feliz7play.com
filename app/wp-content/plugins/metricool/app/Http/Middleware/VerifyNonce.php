<?php

declare(strict_types=1);

namespace Metricool\Http\Middleware;

use Metricool\Interfaces\MiddlewareInterface;
use Metricool\Traits\HasNonces;
use Metricool\Traits\HasRestAccess;

/**
 * Verify the nonce for incoming REST API requests. For methods that modify data (POST, PUT, PATCH, DELETE), the request must include a valid nonce in the 'nonce' parameter.
 */
class VerifyNonce implements MiddlewareInterface
{
    use HasRestAccess;
    use HasNonces;

    public function handle(\WP_REST_Request $request, callable $next)
    {
        $method = $request->get_method();
        $nonce = $request->get_param('nonce');

        // For methods that modify data, verify the nonce
        $methodsRequiringNonce = ['POST', 'PUT', 'PATCH', 'DELETE'];
        if (in_array($method, $methodsRequiringNonce) && ($this->verifyNonce($nonce) === false)) {
            return $this->sendHttpErrorResponse(
                'Forbidden',
                null,
                403
            );
        }

        return $next($request);
    }
}
