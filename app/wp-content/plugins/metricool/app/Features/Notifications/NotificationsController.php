<?php

declare(strict_types=1);

namespace Metricool\Features\Notifications;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Interfaces\FeatureInterface;
use Metricool\Interfaces\NoticeInterface;
use Metricool\Services\DashboardService;
use Metricool\Traits\HasAllowlistControl;

class NotificationsController implements FeatureInterface
{
    use HasAllowlistControl;

    private NotificationsEndpoints $endpoints;
    private NotificationsService $service;
    private NotificationListener $listener;
    private DashboardService $dashboard;

    public function __construct(NotificationsEndpoints $endpoints, NotificationsService $service, NotificationListener $listener, DashboardService $dashboard)
    {
        $this->service = $service;
        $this->endpoints = $endpoints;
        $this->listener = $listener;
        $this->dashboard = $dashboard;
    }

    public function register(): void
    {
        $this->endpoints->register();

        if ($this->userCanManage() && $this->dashboard->isOnboardingCompleted()) {
            $this->listener->listen();
            $this->initiateNotices();
        }

        add_action('metricool_plugin_version_upgrade', [$this, 'upgradeNotices']);
    }

    /**
     * This method returns an array of Notice class-strings that should be added
     * to the database.
     *
     * @internal New Notices should be added here. Upgrade the Notice version if
     * the Notice should be updated. If a Notice should be removed, remove the
     * Notice from this list.
     *
     * @return array<int,class-string<NoticeInterface>> Array of Notice class-strings
     */
    private function getNoticeClassStrings(): array
    {
        return [
            Notices\FirstConnectionNotice::class,
        ];
    }

    /**
     * This method adds the initial Notices to the database if they are not
     * already present.
     * @throws \Exception If notice class cannot be instantiated
     */
    private function initiateNotices(): void
    {
        if ($this->service->hasNotices()) {
            return;
        }

        $this->service->addNotices(
            $this->getNoticeClassStrings()
        );
    }

    /**
     * This method makes sure that if new Notices are added in the update that
     * these Notices are added in the database. Existing Notices will be updated
     * if the version is higher than the current existing Notification with the same id.
     * @throws \Exception If notice class cannot be instantiated
     */
    public function upgradeNotices(): void
    {
        if ($this->service->hasNotices() === false) {
            return; // Notices will be added by initiateNotifications()
        }

        $this->service->upgradeNotices(
            $this->getNoticeClassStrings()
        );
    }
}
