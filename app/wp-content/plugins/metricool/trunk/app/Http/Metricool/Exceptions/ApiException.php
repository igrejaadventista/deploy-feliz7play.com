<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool\Exceptions;

/**
 * Class is used to wrap the exceptions caught in {@see MetricoolClient} and is
 * used to normalize the upstream HTTP status codes.
 */
class ApiException extends \Exception
{
    /**
     * The default upstream HTTP status code to use when the upstream HTTP
     * status code is missing or a server error (5xx).
     */
    private const DEFAULT_UPSTREAM_SERVER_ERROR = 503;

    /**
     * Optional storage for additional data. Can be used to pass data about the
     * failed request to the exception handler via {@see setData()}
     * and {@see getData()}
     */
    protected array $data = [];

    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        $code = $this->normalizeCode($code);

        parent::__construct($message, $code, $previous);
    }

    /**
     * Method to map all missing codes or server errors to
     * {@see DEFAULT_UPSTREAM_SERVER_ERROR}.
     */
    protected function normalizeCode(int $code): int
    {
        $isServerError = ($code >= 500 && $code <= 599);

        if (!empty($code) && !$isServerError) {
            return $code;
        }

        return self::DEFAULT_UPSTREAM_SERVER_ERROR;
    }

    /**
     * Set additional exception {@see data}.
     */
    public function setData(array $data): ApiException
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get additional exception {@see data}.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
