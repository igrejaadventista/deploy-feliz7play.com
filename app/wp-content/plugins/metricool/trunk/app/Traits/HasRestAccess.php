<?php

declare(strict_types=1);

namespace Metricool\Traits;

use Metricool\Support\Helpers\Storage;

trait HasRestAccess
{
    /**
     * Retrieve the parameters from the request.
     *
     * If the data is coming from an AJAX request, its data will be prioritized
     * over the request's JSON parameters.
     *
     * @param string $param - The param to search all the parameters in the
     * request. The key 'data' is often used as the main key. In that case set
     * $param to 'data' to retrieve the parameters from that level.
     */
    public function retrieveHttpParameters(\WP_REST_Request $request, array $ajaxData = [], string $param = ''): array
    {
        if (!empty($param)) {
            return $ajaxData[$param] ?? $request->get_param($param);
        }

        $httpParameters = $ajaxData ?: $request->get_json_params();
        return $httpParameters ?: [];
    }

    /**
     * Retrieve the parameters from the request and store them as Storage.
     * @uses \Metricool\Helpers\Storage
     * @uses HasRestAccess::retrieveHttpParameters
     */
    public function retrieveHttpStorage(\WP_REST_Request $request, array $ajaxData = [], string $param = ''): Storage
    {
        return new Storage(
            $this->retrieveHttpParameters($request, $ajaxData, $param)
        );
    }

    /**
     * Standardized response format
     *
     * @param mixed $data - Data to return
     * @param bool $status - If this action has completed successfully
     * @param string $message - Message to return
     * @param int $code - HTTP status code
     * @return \WP_REST_Response
     */
    public function sendHttpResponse($data = null, bool $status = true, string $message = '', int $code = 200): \WP_REST_Response
    {
        if (ob_get_length()) {
            ob_clean();
        }

        return new \WP_REST_Response([
            'message' => $message,
            'status' => $status ? 'success' : 'error',
            'data' => $data,
            'request_success' => true, // can be used to check if the response in react actually contains this array.
        ], $code ?: 500);
    }

    /**
     * Standardized response error format
     *
     * @param string $message - A user friendly error message
     * @param mixed $data - The data of the error
     * @param int $code - HTTP status code
     * @return \WP_REST_Response
     */
    public function sendHttpErrorResponse(string $message = 'An error occurred', $data = null, int $code = 500): \WP_REST_Response
    {
        $data = (defined('WP_DEBUG') && WP_DEBUG) ? $data : null;
        return $this->sendHttpResponse($data, false, $message, $code);
    }
}
