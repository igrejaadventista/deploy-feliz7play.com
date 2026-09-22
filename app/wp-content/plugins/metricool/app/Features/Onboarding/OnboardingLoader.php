<?php

declare(strict_types=1);

namespace Metricool\Features\Onboarding;

use Metricool\Features\AbstractLoader;
use Metricool\Services\DashboardService;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;
use Metricool\Support\Helpers\Storages\RequestStorage;
use Metricool\Traits\HasAllowlistControl;

class OnboardingLoader extends AbstractLoader
{
    use HasAllowlistControl;

    private DashboardService $dashboard;

    public function __construct(EnvironmentConfig $env, RequestStorage $request, DashboardService $dashboard)
    {
        parent::__construct($env, $request);

        $this->dashboard = $dashboard;
    }

    /**
     * @inheritDoc
     */
    public function isEnabled(): bool
    {
        return $this->dashboard->isOnboardingCompleted() === false;
    }

    /**
     * @inheritDoc
     */
    public function inScope(): bool
    {
        return $this->userCanManage();
    }
}
