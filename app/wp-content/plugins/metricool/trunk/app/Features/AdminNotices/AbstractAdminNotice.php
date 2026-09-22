<?php

declare(strict_types=1);

namespace Metricool\Features\AdminNotices;

use Metricool\Support\Helpers\Storages\EnvironmentConfig;
use Metricool\Traits\HasViews;

abstract class AbstractAdminNotice
{
    use HasViews {
        render as protected traitRender;
    }

    /**
     * The unique id of the Notice should be defined in the child class as a
     * constant. This is used to identify the notice in the database and actions.
     * @var string
     */
    public const IDENTIFIER = '';

    /**
     * The dismiss value in the REST endpoint's action parameter
     * @var string
     */
    public const DISMISS_NOTICE_ACTION = 'dismiss';

    /**
     * The snooze value in the REST endpoint's action parameter
     * @var string
     */
    public const SNOOZE_NOTICE_ACTION = 'snooze';

    protected EnvironmentConfig $env;

    public function __construct(EnvironmentConfig $env)
    {
        if (!defined('static::IDENTIFIER') || empty(static::IDENTIFIER)) {
            throw new \InvalidArgumentException('The IDENTIFIER constant must be defined and not empty in the child class.');
        }

        $this->env = $env;
    }

    /**
     * Notice-specific display conditions, use this to check for things like
     * required capabilities, specific admin pages, etc.
     */
    abstract protected function canDisplay(): bool;

    /**
     * Returns the view path for the inner content of the notice
     */
    abstract protected function getContentView(): string;

    /**
     * Returns the variables passed to the content view
     */
    abstract protected function getContentVariables(): array;

    /**
     * Returns whether the notice can be permanently dismissed
     */
    public function isDismissable(): bool
    {
        return true;
    }

    /**
     * Returns whether the notice can be snoozed (remind me later)
     */
    public function isSnoozable(): bool
    {
        return false;
    }

    /**
     * Returns the snooze duration in days
     */
    public function getSnoozeDays(): int
    {
        return 1;
    }

    /**
     * Returns the URL for the call-to-action button, or empty string if none
     */
    public function getCtaUrl(): string
    {
        return '';
    }

    /**
     * Returns the label for the call-to-action button, or empty string if none
     */
    public function getCtaLabel(): string
    {
        return '';
    }

    /**
     * Render the notice
     */
    public function render(): void
    {
        $this->traitRender('admin/notices/layout', $this->viewData());
    }

    /**
     * Get the unique identifier
     */
    final public function getId(): string
    {
        return static::IDENTIFIER;
    }

    /**
     * Returns whether the notice should be displayed
     */
    final public function shouldDisplay(): bool
    {
        return $this->canDisplay() && !$this->isDismissed() && !$this->isSnoozed();
    }

    /**
     * Snooze the notice for the configured number of days
     */
    final public function snooze(): void
    {
        $snoozedUntil = time() + ($this->getSnoozeDays() * DAY_IN_SECONDS);
        update_option('metricool_notice_' . $this->getId() . '_snoozed_until', $snoozedUntil, false);
    }

    /**
     * Check if the notice has been permanently dismissed
     */
    final public function isDismissed(): bool
    {
        return (bool) get_option('metricool_notice_' . $this->getId() . '_dismissed', false);
    }

    /**
     * Check if the notice is currently snoozed
     */
    final public function isSnoozed(): bool
    {
        $snoozedUntil = (int) get_option('metricool_notice_' . $this->getId() . '_snoozed_until', 0);

        if ($snoozedUntil === 0) {
            return false;
        }

        return time() < $snoozedUntil;
    }

    /**
     * Permanently dismiss the notice
     */
    final public function dismiss(): void
    {
        update_option('metricool_notice_' . $this->getId() . '_dismissed', true, false);
    }

    /**
     * Get the data passed to the notice layout view
     */
    protected function viewData(): array
    {
        $restUrl = rest_url(
            $this->env->getString('http.namespace')
            . '/'
            . $this->env->getString('http.version')
            . '/admin-notices/'
            . $this->getId()
        );

        return [
            'noticeId' => $this->getId(),
            'logoUrl' => $this->env->getUrl('plugin.assets_url') . 'img/mc-logo.svg',
            'restUrl' => $restUrl,
            'isDismissable' => $this->isDismissable(),
            'isSnoozable' => $this->isSnoozable(),
            'ctaUrl' => $this->getCtaUrl(),
            'ctaLabel' => $this->getCtaLabel(),
            'nonce' => wp_create_nonce('metricool_nonce'),
            'wpNonce' => wp_create_nonce('wp_rest'),
            'content' => $this->view($this->getContentView(), $this->getContentVariables()),
        ];
    }
}
