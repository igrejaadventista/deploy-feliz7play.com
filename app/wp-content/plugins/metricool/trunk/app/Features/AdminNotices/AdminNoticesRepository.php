<?php

declare(strict_types=1);

namespace Metricool\Features\AdminNotices;

use Metricool\Bootstrap\App;
use Metricool\Features\AdminNotices\Notices\ReviewNotice;
use Metricool\Features\AdminNotices\Notices\UpgradeNotice;

class AdminNoticesRepository
{
    /**
     * Array of AdminNotice class-strings that should be displayed.
     * @internal New wp-admin notices should be added here
     * @var array<int,class-string<AbstractAdminNotice>>
     */
    public array $adminNoticeClassStrings = [
        ReviewNotice::class,
        UpgradeNotice::class,
    ];

    /** @var AbstractAdminNotice[] */
    private array $notices = [];

    public function __construct()
    {
        $this->initiateNotices();
    }

    protected function initiateNotices(): void
    {
        $app = App::getInstance();
        foreach ($this->adminNoticeClassStrings as $classString) {
            $this->add($app->make($classString));
        }
    }

    /**
     * Add a notice instance to the repository.
     */
    public function add(AbstractAdminNotice $notice): AdminNoticesRepository
    {
        $this->notices[$notice->getId()] = $notice;

        return $this;
    }

    /**
     * Get instances of all admin notices. Notice instances are cached after the first retrieval.
     */
    public function all(): array
    {
        return $this->notices;
    }

    /**
     * Find a notice instance by its identifier
     */
    public function find(string $id): ?AbstractAdminNotice
    {
        return $this->notices[$id] ?? null;
    }

    /**
     * Get all admin notices that should be displayed.
     * @return AbstractAdminNotice[]
     */
    public function getShouldBeDisplayed(): array
    {
        return array_filter($this->notices, fn ($notice) => $notice->shouldDisplay());
    }
}
