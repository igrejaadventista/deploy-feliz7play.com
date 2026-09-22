<?php

declare(strict_types=1);

namespace Metricool\Features\Notifications;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Traits\HasRestAccess;
use Metricool\Traits\HasAllowlistControl;

class NotificationsEndpoints
{
    use HasRestAccess;
    use HasAllowlistControl;

    private NotificationsService $service;

    public function __construct(NotificationsService $service)
    {
        $this->service = $service;
    }

    public function register(): void
    {
        add_filter('metricool_rest_routes', [$this, 'addNotificationRoutes']);
    }

    /**
     * Add the Notification routes to the REST API.
     */
    public function addNotificationRoutes(array $routes): array
    {
        $routes['get_notices'] = [
            'methods' => \WP_REST_Server::READABLE,
            'callback' => [$this, 'getNoticesCallback'],
        ];

        return $routes;
    }

    /**
     * Return current Notices as a WP_REST_Response.
     */
    public function getNoticesCallback(\WP_REST_Request $request): \WP_REST_Response
    {
        $allNoticesAsArray = array_map(function ($notice) {
            return $notice->toArray();
        }, $this->service->getAllNotices());

        return $this->sendHttpResponse(
            array_values($allNoticesAsArray) // Keys should be removed
        );
    }
}
