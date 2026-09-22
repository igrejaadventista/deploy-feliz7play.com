<?php

declare(strict_types=1);

namespace Metricool\Features;

use Metricool\Support\Helpers\Storages\RequestStorage;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

/**
 * Each Feature should have a {FeatureName}Loader class that extends this
 * abstract loader. The {@see FeatureManager} will use the loader to determine
 * if a feature should be loaded.
 *
 * @internal Without loading all the feature classes, composer will prevent
 * requiring the files entirely. Even tho the Feature namespace falls
 * withing the psr-4 scope.
 */
abstract class AbstractLoader
{
    protected EnvironmentConfig $env;
    protected RequestStorage $request;

    public function __construct(EnvironmentConfig $env, RequestStorage $request)
    {
        $this->env = $env;
        $this->request = $request;
    }

    /**
     * Method should return true if the feature is enabled. This can check
     * setting values or user capabilities for example.
     */
    abstract public function isEnabled(): bool;

    /**
     * Method should return true if the context of the user is in the scope of
     * the feature to be loaded. For example: some features only need to load
     * on our dashboard and others also in each REST API request.
     */
    abstract public function inScope(): bool;

    /**
     * Check if the current user is on the Dashboard page.
     * @todo Responsibility for retrieving the dashboard "page" value should
     * be added somewhere and it should be globally accessible.
     */
    protected function userIsOnDashboard(): bool
    {
        $pageVisitedByUser = $this->request->getString('global.page');
        $dashboardUrl = $this->env->getString('plugin.dashboard_url');

        $pluginPageQueryString = wp_parse_url($dashboardUrl, PHP_URL_QUERY);
        parse_str($pluginPageQueryString, $parsedQuery);
        $pluginDashboardPage = ($parsedQuery['page'] ?? '');

        return $pageVisitedByUser === $pluginDashboardPage;
    }

    /**
     * Check if the current request is a WP JSON request. This is better than
     * the WordPress native function `wp_is_json_request()`, because that
     * returns false when visiting /wp-json/ or ?rest_route= (for plain
     * permalinks) endpoint. We need a true value there to activate
     * features that register REST routes. For example
     * {@see \Metricool\Features\Onboarding\OnboardingController}
     *
     * @internal Ignore the phpcs errors for this method, as they are false
     * positives. We do not actually use the $_GET or $_SERVER variables
     * directly, but we need to check if they are set and contain the
     * expected values.
     */
    protected function requestIsRestRequest(): bool
    {
        $pluginHttpNamespace = $this->env->getString('http.namespace');
        $restUrlPrefix = trailingslashit(rest_get_url_prefix());

        $currentRequestUri = isset($_SERVER['REQUEST_URI'])
            ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI']))
            : '';
        $isPlainPermalink = (
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is a REST route detection check, not form processing.
            isset($_GET['rest_route'])
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is a REST route detection check, not form processing.
            && (strpos(sanitize_text_field(wp_unslash($_GET['rest_route'])), $pluginHttpNamespace) !== false)
        );

        return (strpos($currentRequestUri, $restUrlPrefix) !== false) || $isPlainPermalink;
    }
}
