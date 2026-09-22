<?php

namespace Metricool\Features\Onboarding\Services;

use Metricool\Exceptions\RestDataException;
use Metricool\Features\Onboarding\Exceptions\CreateAccountException;
use Metricool\Features\Onboarding\OnboardingService;
use Metricool\Http\Metricool\Exceptions\ApiException;
use Metricool\Http\Metricool\MetricoolApi;
use Metricool\Http\RSPAL\RspalApiClient;
use Metricool\Services\DashboardService;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;
use Throwable;

class CreateAccountService
{
    private RspalApiClient $rspal;
    private MetricoolApi $api;
    private EnvironmentConfig $env;
    private OnboardingService $onboarding;
    private OAuthService $oauth;
    private DashboardService $dashboard;

    public function __construct(
        RspalApiClient $rspal,
        MetricoolApi $api,
        EnvironmentConfig $env,
        OnboardingService $onboarding,
        OAuthService $oauth,
        DashboardService $dashboard
    ) {
        $this->rspal = $rspal;
        $this->api = $api;
        $this->env = $env;
        $this->onboarding = $onboarding;
        $this->oauth = $oauth;
        $this->dashboard = $dashboard;
    }

    /**
     * Creates a new Metricool account and authenticate the MetricoolClient.
     * @throws CreateAccountException
     */
    public function createAccount(string $captcha, string $email, string $password, bool $newsletters): bool
    {
        $globalError = sprintf(
            /* translators: %1$s is opening link and %2$s is closing link */
            __('Something went wrong. Please try again or %1$sleave a support message%2$s.', 'metricool'),
            '<a href="' . $this->env->get('frontend.trusted_urls.new_support_ticket') . '" target="_blank">',
            '</a>',
        );

        // First, check if the password is valid
        if (!$this->isValidPassword($password)) {
            throw new CreateAccountException(esc_html__('Password is not valid.', 'metricool'), 'Password is not valid', 422);
        }

        // Attempt to create the account
        try {
            $signupResponse = $this->rspal->signUp([
                'username' => $email,
                'newsletters' => $newsletters,
            ], [
                'RSPAL-RecaptchaV3Token' => $captcha
            ]);
        } catch (RestDataException $e) {
            throw new CreateAccountException(wp_kses_post($globalError), esc_html($e->getMessage()), $e->getResponseCode());
        }

        // Parse the user ID from the access token
        $userId = $this->oauth->parseUserIdFromAccessToken($signupResponse->data->accessToken);
        if (empty($userId)) {
            throw new CreateAccountException(wp_kses_post($globalError), 'Failed to parse user ID from access token', 500);
        }

        // Authenticate the Metricool API Client
        $this->api->authenticate(
            $userId,
            (string) $signupResponse->data->accessToken,
            (string) $signupResponse->data->refreshToken,
            $signupResponse->data->expires ?? 300
        );

        // Update the user's password
        try {
            $this->api->userCredentials()
                ->updatePassword('', $password);
        } catch (ApiException $e) {
            $this->api->logout();

            throw new CreateAccountException(wp_kses_post($globalError), esc_html($e->getMessage()), 500);
        }

        // Attempt to automatically set the blog information, complete the onboarding process on success
        try {
            $this->onboarding->finalizeOnboarding($this->findConnectedBrandId());
        } catch (Throwable $e) {
            throw new CreateAccountException(wp_kses_post($globalError), esc_html($e->getMessage()), $e->getCode());
        }

        $this->dashboard->setShowWelcomeScreen();

        return true;
    }


    /**
     * Get the blogId from the API. This is needed to connect the brand and complete the onboarding process.
     * @throws ApiException
     */
    private function findConnectedBrandId(): ?string
    {
        $brands = $this->api->brands()->all();

        // Get the brand when there is only one, abort if there are more
        $brand = (count($brands) === 1 ? (array) $brands[0] : []);

        return $brand['id'] ?? null;
    }

    /**
     * Check if the password is: between 8 and 20 characters long, contains at least one uppercase letter, one lowercase letter, one number, and one special character
     */
    protected function isValidPassword(string $password): bool
    {
        return (bool) preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,20}$/', $password);
    }
}
