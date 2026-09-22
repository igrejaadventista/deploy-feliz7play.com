<?php

namespace Metricool\Services;

use Metricool\Support\Helpers\Storages\RequestStorage;
use Metricool\Http\Metricool\MetricoolApi;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class DashboardService
{
    public const ONBOARDING_COMPLETED_OPTION = 'metricool_onboarding_completed_at';
    public const FORCED_LOGIN_OPTION = 'metricool_force_login';

    private EnvironmentConfig $env;
    private MetricoolApi $api;
    private RequestStorage $request;

    public function __construct(EnvironmentConfig $env, MetricoolApi $api, RequestStorage $request)
    {
        $this->env = $env;
        $this->api = $api;
        $this->request = $request;
    }

    /**
     * Returns the current state of the onboarding process
     *
     *     [
     *         // the onboarding is completed, the user can start using the plugin
     *         'completed' => true,
     *         // the plugin is authenticated with the Metricool API
     *         'authenticated' => true,
     *         // submit a blog_id when false
     *         'blog_id_selected' => true
     *     ]
     */
    public function state(): array
    {
        return [
            'completed' => $this->isOnboardingCompleted(),
            'authenticated' => $this->api->hasAuthentication(),
            'blog_id_selected' => $this->api->hasBlogId(),
        ];
    }

    /**
     * Returns the current mode of the onboarding process
     *
     *     [
     *         // the welcome screen should be shown
     *         'show_welcome_screen' => true,
     *         // force the user to log in with a forced login screen
     *         'forced_login' => true
     *     ]
     */
    public function mode(): array
    {
        return [
            'show_welcome_screen' => $this->shouldShowWelcomeScreen(),
            'forced_login' => $this->isForcedLogin(),
        ];
    }

    /**
     * Enable the forced login screen
     */
    public function enableForcedLogin(): bool
    {
        return $this->setForcedLogin(true);
    }

    /**
     * Disable the forced login state
     */
    public function disableForcedLogin(): bool
    {
        return delete_option(self::FORCED_LOGIN_OPTION);
    }

    /**
     * Check if the front-end should show the forced login screen
     */
    public function isForcedLogin(): bool
    {
        return (bool) get_option(self::FORCED_LOGIN_OPTION, false);
    }

    /**
     * Set the forced login state
     */
    public function setForcedLogin(bool $forcedLogin): bool
    {
        return update_option(self::FORCED_LOGIN_OPTION, $forcedLogin, false);
    }

    /**
     * Check if the current admin screen is the Leadinfo dashboard page
     */
    public function isUserOnDashboard(): bool
    {
        $pageVisitedByUser = $this->request->getString('global.page');
        $dashboardUrl = $this->env->getString('plugin.dashboard_url');

        $pluginPageQueryString = wp_parse_url($dashboardUrl, PHP_URL_QUERY);
        parse_str($pluginPageQueryString, $parsedQuery);
        $pluginDashboardPage = ($parsedQuery['page'] ?? '');

        return $pageVisitedByUser === $pluginDashboardPage;
    }

    /**
     * Check if the onboarding was completed
     */
    public function isOnboardingCompleted(): bool
    {
        return $this->api->hasUserToken() && $this->api->hasBlogId() && get_option(self::ONBOARDING_COMPLETED_OPTION, false);
    }

    /**
     * Completes the onboarding process by removing all onboarding data and storing the timestamp
     */
    public function setOnboardingCompleted(): bool
    {
        $timestamp = time();

        do_action('metricool_onboarding_completed', $timestamp);

        return update_option(self::ONBOARDING_COMPLETED_OPTION, $timestamp, false);
    }

    /**
     * Check if the welcome screen should be shown
     */
    public function shouldShowWelcomeScreen(): bool
    {
        return $this->isOnboardingCompleted() && $this->showWelcomeScreenOnce();
    }

    /**
     * Check if the welcome screen should be shown once
     */
    public function showWelcomeScreenOnce(): bool
    {
        $show = (bool) get_option('metricool_show_welcome_screen', false);
        delete_option('metricool_show_welcome_screen');

        return $show;
    }

    /**
     * Set the welcome screen as shown
     */
    public function setShowWelcomeScreen(): void
    {
        update_option('metricool_show_welcome_screen', true, false);
    }
}
