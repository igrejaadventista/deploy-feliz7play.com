<?php

declare(strict_types=1);

namespace Metricool\Features\AdminNotices;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Interfaces\FeatureInterface;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class AdminNoticesController implements FeatureInterface
{
    private EnvironmentConfig $env;
    private AdminNoticesEndpoints $endpoints;
    private AdminNoticesRepository $repository;

    public function __construct(EnvironmentConfig $env, AdminNoticesEndpoints $endpoints, AdminNoticesRepository $repository)
    {
        $this->env = $env;
        $this->endpoints = $endpoints;
        $this->repository = $repository;
    }

    public function register(): void
    {
        add_action('admin_init', [$this, 'renderNotices']);

        $this->endpoints->register();
    }

    /**
     * Render all admin notices that should be displayed
     */
    public function renderNotices(): void
    {
        $notices = $this->repository->getShouldBeDisplayed();

        if (count($notices) === 0) {
            return;
        }

        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);

        add_action('admin_notices', static function () use ($notices) {
            foreach ($notices as $notice) {
                $notice->render();
            }
        });
    }

    /**
     * Enqueue the javascript for the admin notices
     */
    public function enqueueScripts(): void
    {
        wp_enqueue_script(
            'metricool-admin-notice',
            $this->env->getUrl('plugin.assets_url') . 'js/admin-notice.js',
            [],
            $this->env->getString('plugin.version'),
            true
        );
    }
}
