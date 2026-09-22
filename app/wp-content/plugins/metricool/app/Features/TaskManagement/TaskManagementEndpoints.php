<?php

declare(strict_types=1);

namespace Metricool\Features\TaskManagement;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Traits\HasRestAccess;
use Metricool\Traits\HasAllowlistControl;
use Metricool\Features\TaskManagement\Exceptions\DismissRequiredTaskException;

class TaskManagementEndpoints
{
    use HasRestAccess;
    use HasAllowlistControl;

    private TaskManagementService $service;

    public function __construct(TaskManagementService $service)
    {
        $this->service = $service;
    }

    public function register(): void
    {
        add_filter('metricool_rest_routes', [$this, 'addTaskRoutes']);
    }

    /**
     * Add the task routes to the REST API.
     */
    public function addTaskRoutes(array $routes): array
    {
        $routes['get_tasks'] = [
            'methods' => \WP_REST_Server::READABLE,
            'callback' => [$this, 'getTasksCallback'],
        ];

        $routes['dismiss_task'] = [
            'methods' => \WP_REST_Server::CREATABLE,
            'callback' => [$this, 'dismissTaskCallback'],
        ];

        return $routes;
    }

    /**
     * Return current tasks as a WP_REST_Response.
     */
    public function getTasksCallback(\WP_REST_Request $request): \WP_REST_Response
    {
        $allTasksAsArray = array_map(function ($task) {
            return $task->toArray();
        }, $this->service->getAllTasks(true));

        return $this->sendHttpResponse(
            array_values($allTasksAsArray) // Keys should be removed
        );
    }

    /**
     * Dismiss a task by taskId.
     */
    public function dismissTaskCallback(\WP_REST_Request $request): \WP_REST_Response
    {
        $storage = $this->retrieveHttpStorage($request);

        $sanitizedTaskId = $storage->getTitle('taskId');
        $task = $this->service->getTask($sanitizedTaskId);

        if (!$task) {
            return $this->sendHttpErrorResponse(__('Task not found', 'metricool'), null, 404);
        }

        // Attempt to dismiss the task
        try {
            $this->service->dismissTask($task->getId());
        } catch (DismissRequiredTaskException $e) {
            return $this->sendHttpErrorResponse(__('This task cannot be dismissed because it is required', 'metricool'));
        }

        return $this->sendHttpResponse(['taskId' => $sanitizedTaskId], true, __('Task dismissed successfully', 'metricool'));
    }
}
