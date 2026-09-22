<?php

declare(strict_types=1);

namespace Metricool\Features\AdminNotices;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Support\Helpers\Storage;
use Metricool\Traits\HasRestAccess;
use Metricool\Traits\HasAllowlistControl;

class AdminNoticesEndpoints
{
    use HasRestAccess;
    use HasAllowlistControl;

    private AdminNoticesRepository $repository;

    public function __construct(AdminNoticesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function register(): void
    {
        add_filter('metricool_rest_routes', [$this, 'addRestRoutes']);
    }

    public function addRestRoutes(array $routes): array
    {
        $routes['/admin-notices/(?P<notice_id>[\w-]+)'] = [
            'methods' => 'POST',
            'callback' => [$this, 'handleNoticeAction'],
            'args' => [
                'notice_id' => [
                    'required' => true,
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ],
                'action' => [
                    'required' => true,
                    'type' => 'string',
                    'enum' => [AbstractAdminNotice::DISMISS_NOTICE_ACTION, AbstractAdminNotice::SNOOZE_NOTICE_ACTION],
                ],
            ],
        ];

        return $routes;
    }

    public function handleNoticeAction(\WP_REST_Request $wpRestRequest): \WP_REST_Response
    {
        $request = new Storage(
            $wpRestRequest->get_params()
        );

        $noticeId = $request->getString('notice_id');
        $action = $request->getString('action');

        $notice = $this->repository->find($noticeId);
        if ($notice === null) {
            return $this->sendHttpErrorResponse('Notice not found', null, 404);
        }

        if ($action === AbstractAdminNotice::DISMISS_NOTICE_ACTION) {
            $notice->dismiss();
        }

        if ($action === AbstractAdminNotice::SNOOZE_NOTICE_ACTION) {
            $notice->snooze();
        }

        return $this->sendHttpResponse(['success' => true]);
    }
}
