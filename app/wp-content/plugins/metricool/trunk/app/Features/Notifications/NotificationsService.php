<?php

declare(strict_types=1);

namespace Metricool\Features\Notifications;

use Metricool\Bootstrap\App;
use Metricool\Interfaces\NoticeInterface;

class NotificationsService
{
    private NotificationsRepository $repository;

    public function __construct(NotificationsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Check if there are Notices
     */
    public function hasNotices(): bool
    {
        return !empty($this->getAllNotices());
    }

    /**
     * Get all Notices
     * @return NoticeInterface[]
     */
    public function getAllNotices(): array
    {
        return $this->repository->getAllNotices();
    }

    /**
     * Add multiple Notices at once
     * @param class-string<NoticeInterface>[] $notices
     * @throws \Exception If notice class cannot be instantiated
     */
    public function addNotices(array $notices): void
    {
        foreach ($notices as $noticeClassString) {
            $notice = App::getInstance()->make($noticeClassString);
            $this->repository->addNotice($notice, false);
        }
        $this->repository->saveNoticesToDatabase();
    }

    /**
     * Upgrade the Notices. Only replace existing Notices with same identifier
     * if the version is lower than the new Notice version. Add missing Notices
     * and remove Notices that are no longer present.
     * @param class-string<NoticeInterface>[] $notices
     * @throws \Exception If notice class cannot be instantiated
     */
    public function upgradeNotices(array $notices): void
    {
        // Remove Notices that are no longer present. Maybe that are them all?
        $deletableNoticeList = $this->repository->getAllNotices();

        foreach ($notices as $noticeClassString) {
            $notice = App::getInstance()->make($noticeClassString);

            $this->repository->upgradeNotice($notice, false);

            // Current Notices is not deletable so remove it from the list
            unset($deletableNoticeList[$notice->getId()]);
        }

        // If list still contains Notices, the upgrade requests them to be
        // removed
        if (!empty($deletableNoticeList)) {
            $this->removeDeletableNoticesAfterUpgrade($deletableNoticeList, false);
        }

        $this->repository->saveNoticesToDatabase();
    }

    /**
     * Remove Notices that are no longer present in our Notice Object list. Such
     * Notices are now a __PHP_Incomplete_Class and do not implement the
     * NoticeInterface. Because of this we cannot use the Notice classes.
     */
    private function removeDeletableNoticesAfterUpgrade(array $deletableNoticesList, bool $save = true): void
    {
        foreach ($deletableNoticesList as $noticeId => $deletedNotice) {
            $this->repository->removeNoticeById($noticeId, $save);
        }

        if ($save) {
            $this->repository->saveNoticesToDatabase();
        }
    }

    /**
     * Remove multiple Notices at once
     * @param NoticeInterface[] $notices
     */
    public function removeNotices(array $notices, bool $save = true): void
    {
        foreach ($notices as $notice) {
            $this->repository->removeNotice($notice, $save);
        }

        if ($save) {
            $this->repository->saveNoticesToDatabase();
        }
    }

    /**
     * Activate a task by default.
     */
    public function activate(string $noticeID): void
    {
        $this->repository->toggleNoticeServerSide($noticeID, true);
    }

    /**
     * Deactivate a task by default.
     */
    public function deactivate(string $noticeID): void
    {
        $this->repository->toggleNoticeServerSide($noticeID, false);
    }
}
