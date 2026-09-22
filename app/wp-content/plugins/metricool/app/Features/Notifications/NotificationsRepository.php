<?php

declare(strict_types=1);

namespace Metricool\Features\Notifications;

use Metricool\Interfaces\NoticeInterface;

class NotificationsRepository
{
    public const OPTION_NAME = 'metricool_notices';

    /** @var NoticeInterface[] */
    private array $notices = [];

    public function __construct()
    {
        $this->loadNoticesFromDatabase();
    }

    /**
     * Retrieve a single Notice by its ID
     */
    public function getNotice(string $noticeId): ?NoticeInterface
    {
        return $this->notices[$noticeId] ?? null;
    }

    /**
     * Retrieve all registered notices
     * @return NoticeInterface[]
     */
    public function getAllNotices(): array
    {
        return $this->notices;
    }

    /**
     * Add a single Notice to the repository
     */
    public function addNotice(NoticeInterface $notice, bool $save = true): void
    {
        $this->notices[$notice->getId()] = $notice;

        if ($save) {
            $this->saveNoticesToDatabase();
        }
    }

    /**
     * Upgrade a Notice in the repository. Only replace existing Notices with
     * same identifier if the version is lower than the new Notice version.
     */
    public function upgradeNotice(NoticeInterface $notice, bool $save = true): void
    {
        $existingNotice = $this->getNotice($notice->getId());
        $noticeExists = !empty($existingNotice);

        $noticeIsUpdatable = (
            !$noticeExists
            || (version_compare($existingNotice->getVersion(), $notice->getVersion(), '<'))
        );

        if ($noticeIsUpdatable === false) {
            return;
        }

        // Upgrades existing Notices and add new Notices
        $this->addNotice($notice, $save);
    }

    /**
     * Remove a Notice from the repository
     */
    public function removeNotice(NoticeInterface $notice, bool $save = true): void
    {
        unset($this->notices[$notice->getId()]);

        if ($save) {
            $this->saveNoticesToDatabase();
        }
    }

    /**
     * Remove a Notice by its ID from the repository
     */
    public function removeNoticeById(string $noticeId, bool $save = true): void
    {
        if (isset($this->notices[$noticeId])) {
            unset($this->notices[$noticeId]);
        }

        if ($save) {
            $this->saveNoticesToDatabase();
        }
    }

    /**
     * Update the status of a Notice if the Notice exists. If the Notice is
     * required and the status is set to 'dismissed', the status will not be
     * updated.
     */
    public function toggleNoticeServerSide(string $noticeId, bool $active): void
    {
        $notice = $this->getNotice($noticeId);
        if ($notice === null) {
            return;
        }

        $notice->setActive($active);
        $this->addNotice($notice);
    }

    /**
     * Load notices from the WordPress database
     */
    private function loadNoticesFromDatabase(): void
    {
        $storedNotices = get_option(self::OPTION_NAME, []);
        $this->notices = is_array($storedNotices) ? $storedNotices : [];
    }

    /**
     * Save notices to the WordPress database
     */
    public function saveNoticesToDatabase(): void
    {
        update_option(self::OPTION_NAME, $this->notices, false);
    }
}
