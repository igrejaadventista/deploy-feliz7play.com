<?php

declare(strict_types=1);

namespace Metricool\Controllers;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Traits\HasViews;
use Metricool\Services\TrackingScriptService;
use Metricool\Interfaces\ControllerInterface;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class TrackingScriptController implements ControllerInterface
{
    use HasViews;

    private EnvironmentConfig $env;
    private TrackingScriptService $service;

    public function __construct(EnvironmentConfig $env, TrackingScriptService $service)
    {
        $this->env = $env;
        $this->service = $service;
    }

    public function register(): void
    {
        add_action('wp_footer', [$this, 'renderTrackingWidget']);
    }

    public function renderTrackingWidget(): void
    {
        if ($this->canRenderTrackingScript() === false) {
            return;
        }

        $this->render('public/tracking-script', [
            'script_url' => $this->env->getUrl('metricool.tracking_script_url'),
            'hash' => $this->service->getTrackingHash(),
        ]);
    }

    /**
     * Checks if the tracking script can be rendered. Only if the tracking hash
     * is set and the user has enabled the widget.
     */
    private function canRenderTrackingScript(): bool
    {
        $outOfScope = (is_admin() || is_feed() || is_robots() || is_trackback());
        $trackingHashIsNotEmpty = (strlen($this->service->getTrackingHash()) > 0);

        return $trackingHashIsNotEmpty && $this->service->isTrackingWidgetActive() && ($outOfScope === false);
    }
}
