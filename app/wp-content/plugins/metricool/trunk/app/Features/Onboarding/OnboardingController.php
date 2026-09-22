<?php

declare(strict_types=1);

namespace Metricool\Features\Onboarding;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Support\Helpers\Storages\RequestStorage;
use Metricool\Traits\HasRestAccess;
use Metricool\Services\DashboardService;
use Metricool\Support\Validation\Validator;
use Metricool\Interfaces\FeatureInterface;
use Metricool\Services\MetricoolAccountService;
use Metricool\Features\Onboarding\Services\OAuthService;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;
use Metricool\Features\Onboarding\Services\CreateAccountService;
use Metricool\Features\Onboarding\Exceptions\CreateAccountException;
use Metricool\Features\Onboarding\Exceptions\BrandAccessDeniedException;
use Throwable;

class OnboardingController implements FeatureInterface
{
    use HasRestAccess;

    private OnboardingService $onboarding;
    private EnvironmentConfig $env;
    private CreateAccountService $accounts;
    private OAuthService $oauth;
    private DashboardService $dashboard;
    private MetricoolAccountService $account;
    private RequestStorage $request;

    public function __construct(
        OnboardingService $onboarding,
        CreateAccountService $accounts,
        EnvironmentConfig $env,
        OAuthService $oauth,
        DashboardService $dashboard,
        MetricoolAccountService $account,
        RequestStorage $request
    ) {
        $this->onboarding = $onboarding;
        $this->accounts = $accounts;
        $this->env = $env;
        $this->oauth = $oauth;
        $this->dashboard = $dashboard;
        $this->account = $account;
        $this->request = $request;
    }

    public function register(): void
    {
        add_filter('metricool_rest_routes', [$this, 'addRoutes']);

        // Intercept OAuth callback on the dashboard page
        add_action('load-toplevel_page_metricool', [$this, 'oauthCallback']);
    }

    /**
     * Add onboarding routes to the existing routes of our plugin
     */
    public function addRoutes(array $routes): array
    {
        $routes['onboarding/create_account'] = [
            'methods' => 'POST',
            'callback' => [$this, 'createAccount'],
        ];

        $routes['onboarding/finish_onboarding'] = [
            'methods' => 'POST',
            'callback' => [$this, 'finishOnboarding'],
        ];

        $routes['onboarding/oauth_redirect'] = [
            'methods' => 'GET',
            'callback' => [$this, 'getOauthRedirectUrl'],
        ];

        return $routes;
    }

    /**
     * Create a new Metricool account. The created user is authenticated
     * automatically.
     */
    public function createAccount(\WP_REST_Request $request): \WP_REST_Response
    {
        $validated = Validator::validate($request->get_params(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'captcha' => 'required|string',
            'terms' => 'accepted',
            'marketing' => 'boolean',
        ]);

        // Attempt to create the account
        try {
            $this->accounts->createAccount(
                $validated['captcha'],
                $validated['email'],
                $validated['password'],
                rest_sanitize_boolean($validated['marketing'] ?? false)
            );
        } catch (CreateAccountException $e) {
            return $this->sendHttpErrorResponse($e->getMessage(), ['reason' => $e->getReason()], $e->getCode());
        }

        return $this->onboardedResponse();
    }

    /**
     * Method is used to finish the onboarding process. It is called when the
     * user has completed the onboarding process and wants to finish it.
     */
    public function finishOnboarding(\WP_REST_Request $request): \WP_REST_Response
    {
        $blogId = (string) $request->get_param('blogId');

        if (empty($blogId)) {
            return $this->onboardedResponse();
        }

        // Store the blogId if it was provided by the client, to retrieve the necessary blog information
        try {
            $this->onboarding->finalizeOnboarding($blogId);
        } catch (BrandAccessDeniedException $e) {
            return $this->sendHttpErrorResponse(
                __('You don’t have sufficient permissions to connect this brand. Please pick another brand or sign in with a different account.', 'metricool'),
                [],
                403
            );
        } catch (Throwable $e) {
            return $this->sendHttpErrorResponse(wp_kses_post(sprintf(
                /* translators: %1$s is opening link and %2$s is closing link */
                __('Something went wrong. Please try again or %1$sleave a support message%2$s.', 'metricool'),
                '<a href="' . $this->env->get('frontend.trusted_urls.new_support_ticket') . '" target="_blank">',
                '</a>',
            )), ['data' => $e->getMessage()], $e->getCode() ?: 500);
        }

        return $this->onboardedResponse();
    }

    /**
     * Build and return the Metricool OAuth authorize URL.
     */
    public function getOauthRedirectUrl(\WP_REST_Request $request): \WP_REST_Response
    {
        if (is_ssl() === false) {
            return $this->sendHttpErrorResponse(__('HTTPS is required to be able to authorize this website.', 'metricool'), [], 400);
        }

        return $this->sendHttpResponse([
            'redirect_url' => $this->oauth->getAuthorizationUrl(),
        ]);
    }

    /**
     * Handle the OAuth callback from Metricool. Exchanges the authorization
     * code for tokens, authenticates the user, and redirects to the dashboard.
     */
    public function oauthCallback(): void
    {
        $action = $this->request->getString('global.metricool_action');

        if ($action !== OAuthService::REDIRECT_ACTION) {
            return;
        }

        $code = $this->request->getString('global.code');
        $state = $this->request->getString('global.state');

        try {
            $this->oauth->authenticateWithCode($code, $state);
        } catch (Throwable $e) {
            wp_safe_redirect(add_query_arg('oauth_error', $e->getMessage(), $this->env->getString('plugin.dashboard_url')));
            exit;
        }

        // Attempt to automatically set the blog information, completes the onboarding process on success
        try {
            $this->onboarding->finalizeOnboarding();
        } catch (Throwable $e) {
            wp_safe_redirect(add_query_arg('oauth_error', 'onboarding_failed', $this->env->getString('plugin.dashboard_url')));
            exit;
        }

        // Redirect to the WordPress dashboard
        wp_safe_redirect($this->env->getString('plugin.dashboard_url'));
        exit;
    }

    /**
     * Response that is returned when the onboarding process has been completed successfully.
     * Includes the onboarding state and mode, as well as the account data of the user.
     */
    private function onboardedResponse(): \WP_REST_Response
    {
        return $this->sendHttpResponse([
            'onboarding' => [
                'state' => $this->dashboard->state(),
                'mode' => $this->dashboard->mode(),
            ],
            'account' => $this->account->accountData(),
        ]);
    }
}
