<?php

declare(strict_types=1);

namespace Metricool\Http\Endpoints\Responses;

/**
 * Response class that serves as a blueprint for creating custom Responses for WordPress REST API endpoints.
 */
abstract class Response
{
    /**
     * Creates the response body
     */
    abstract public function body(): array;
}
