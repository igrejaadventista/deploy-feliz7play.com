<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Features\UserSettings\Exceptions\StorageSubmitException;
use Metricool\Features\UserSettings\Exceptions\ValidationFailedExceptions;
use Metricool\Traits\HasAllowlistControl;
use Metricool\Traits\HasRestAccess;

class UserSettingsEndpoint
{
    use HasRestAccess;
    use HasAllowlistControl;

    private UserSettingsService $service;

    public function __construct(UserSettingsService $service)
    {
        $this->service = $service;
    }

    public function register(): void
    {
        add_filter('metricool_rest_routes', [$this, 'addRoutes']);
    }

    /**
     * Add the task routes to the REST API.
     */
    public function addRoutes(array $routes): array
    {
        $routeParameters = [
            'methods' => \WP_REST_Server::READABLE . ', ' . \WP_REST_Server::EDITABLE,
            'callback' => [$this, 'callback'],
        ];

        $routes['user_settings'] = $routeParameters;
        $routes['user_settings/(?P<section>[^/]+)'] = $routeParameters;

        return $routes;
    }

    /**
     * Handle the REST API request, routing to the appropriate method to the
     * correct method. Only allows GET, POST, PUT, and PATCH.
     */
    public function callback(\WP_REST_Request $request): \WP_REST_Response
    {
        switch ($request->get_method()) {
            case \WP_REST_Server::READABLE:
                return $this->getUserSettings($request);
            case 'POST':
            case 'PUT':
            case 'PATCH':
                return $this->storeUserSettings($request);
            default:
                return $this->sendHttpResponse([], false, 'Method not allowed', 405);
        }
    }

    /**
     * Retrieve user settings, optionally filtered by section. Sends an error
     * response if something goes wrong.
     */
    protected function getUserSettings(\WP_REST_Request $request): \WP_REST_Response
    {
        try {
            $settings = $this->service->getSettingsResponse(
                $request->get_param('section')
            );
        } catch (\Exception $e) {
            return $this->sendHttpErrorResponse(__('Failed to retrieve settings', 'metricool'), $e->getMessage());
        }

        return $this->sendHttpResponse($settings);
    }

    /**
     * Store user settings. Sends an error response if something goes wrong.
     */
    protected function storeUserSettings(\WP_REST_Request $request): \WP_REST_Response
    {
        try {
            $settings = $request->get_params();
            $updatedSettings = $this->service->storeSettings($settings, $request);
        } catch (ValidationFailedExceptions $e) {
            return $this->sendHttpResponse($e->getErrors(), false, __('Validation failed', 'metricool'), 422);
        } catch (StorageSubmitException $e) {
            return $this->sendHttpErrorResponse($e->getMessage(), $e->getErrors(), 502);
        } catch (\Exception $e) {
            return $this->sendHttpErrorResponse(__('An unknown error occurred. Failed to update the settings!', 'metricool'), $e->getMessage());
        }

        $response = new UserSettingsResponse($updatedSettings);

        return $this->sendHttpResponse(
            $response->parse()->get()
        );
    }
}
