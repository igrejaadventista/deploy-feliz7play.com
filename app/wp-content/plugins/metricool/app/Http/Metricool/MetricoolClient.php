<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool;

use InvalidArgumentException;
use Metricool\Vendor\Carbon\Carbon;
use Metricool\Vendor\GuzzleHttp\Client;
use Metricool\Vendor\GuzzleHttp\Psr7\Request;
use Metricool\Services\OptionsService;
use Metricool\Services\TrackingScriptService;
use Metricool\Vendor\Psr\Http\Message\ResponseInterface;
use Metricool\Http\Metricool\Exceptions\ApiException;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;
use RuntimeException;
use Throwable;

class MetricoolClient
{
    private const OPTION_USER_ID = 'metricool_user_id';
    private const OPTION_BLOG_ID = 'metricool_blog_id';
    private const OPTION_AUTH_TOKEN = 'metricool_auth_token';
    private const OPTION_REFRESH_TOKEN = 'metricool_refresh_token';
    private const OPTION_AUTH_TOKEN_EXPIRES = 'metricool_auth_token_expires';
    private const OPTION_REFRESH_LOCK = 'metricool_refresh_lock';

    /**
     * The amount of milliseconds to wait before polling for a new token
     */
    private const REFRESH_LOCK_WAIT_MS = 100;
    /**
     * The timeout for the token refresh request
     */
    public const REFRESH_TIMEOUT_SECONDS = 10;

    private Client $client;

    private EnvironmentConfig $env;
    private OptionsService $options;

    private string $apiUrl;
    protected array $middleWares = [];


    /**
     * Create a new Metricool API client wrapper.
     */
    public function __construct(EnvironmentConfig $env, OptionsService $options)
    {
        $this->env = $env;
        $this->options = $options;
        $this->apiUrl = $env->get('metricool.base_api_domain');
        $this->client = $this->client();
    }

    /**
     * Set the authenticated Metricool user ID.
     */
    public function setUserId(string $userId): void
    {
        update_option(self::OPTION_USER_ID, $userId, false);
    }

    /**
     * Get the authenticated Metricool user ID.
     */
    public function getUserId(): ?string
    {
        return get_option(self::OPTION_USER_ID, null);
    }

    /**
     * Check whether a Metricool user ID is available.
     */
    public function hasUserId(): bool
    {
        return !empty($this->getUserId());
    }

    /**
     * Persist and set the Metricool user ID.
     */
    public function storeUserId(string $userId): void
    {
        update_option(self::OPTION_USER_ID, $userId, false);
    }

    /**
     * Clear the persisted Metricool user ID.
     */
    public function clearUserId(): void
    {
        delete_option(self::OPTION_USER_ID);
    }

    /**
     * Get the selected Metricool blog ID.
     */
    public function getBlogId(): ?string
    {
        return get_option(self::OPTION_BLOG_ID, null);
    }

    /**
     * Persist and set the Metricool blog ID.
     */
    public function storeBlogId(string $blogId): void
    {
        update_option(self::OPTION_BLOG_ID, $blogId, false);
    }

    /**
     * Clear the persisted Metricool blog ID.
     */
    public function clearBlogId(): void
    {
        delete_option(self::OPTION_BLOG_ID);
    }

    /**
     * Check whether a Metricool blog ID is available.
     */
    public function hasBlogId(): bool
    {
        return !empty($this->getBlogId());
    }

    /**
     * Get the current access token.
     */
    public function getUserToken(): ?string
    {
        return get_option(self::OPTION_AUTH_TOKEN, null);
    }

    /**
     * Check whether an access token is available.
     */
    public function hasUserToken(): bool
    {
        return !empty($this->getUserToken());
    }

    /**
     * Persist and set the current access token.
     */
    public function storeUserToken(string $token): void
    {
        update_option(self::OPTION_AUTH_TOKEN, $token, false);
    }

    /**
     * Clear the persisted access token.
     */
    public function clearUserToken(): void
    {
        delete_option(self::OPTION_AUTH_TOKEN);
    }

    /**
     * Get the persisted refresh token.
     */
    public function getRefreshToken(): ?string
    {
        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching - Refresh token is not cached
        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1",
                self::OPTION_REFRESH_TOKEN
            )
        );
    }

    /**
     * Persist the refresh token.
     */
    public function storeRefreshToken(string $refreshToken): void
    {
        update_option(self::OPTION_REFRESH_TOKEN, $refreshToken, false);
    }

    /**
     * Clear the persisted refresh token data.
     */
    public function clearRefreshToken(): void
    {
        delete_option(self::OPTION_REFRESH_TOKEN);
        delete_option(self::OPTION_AUTH_TOKEN_EXPIRES);
    }

    /**
     * Get the token expiration timestamp with a raw query to avoid retrieving
     * the option from the WordPress object cache.
     * @internal Not using get_option() is on purpose!
     */
    public function getTokenExpires(): int
    {
        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        return (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1",
                self::OPTION_AUTH_TOKEN_EXPIRES
            )
        );
    }

    /**
     * Get the token expiration as a Carbon date.
     */
    public function tokenExpiresAt(): Carbon
    {
        return Carbon::createFromTimestamp($this->getTokenExpires());
    }

    /**
     * Determine whether the access token is expired. This uses a 1-minute buffer,
     * to account for clock skew and request time.
     */
    public function isTokenExpired(): bool
    {
        return Carbon::now()->gt($this->tokenExpiresAt()->subMinute());
    }

    /**
     * Persist the token expiration time.
     */
    public function storeTokenExpires(int $expiresIn): void
    {
        $expiresIn = Carbon::now()->addSeconds($expiresIn)->timestamp;

        update_option(self::OPTION_AUTH_TOKEN_EXPIRES, $expiresIn, false);
    }

    /**
     * Set the authentication tokens and userId.
     */
    public function authenticate(string $userId, string $userToken, string $refreshToken, int $expires): self
    {
        $this->storeUserId($userId);
        $this->storeUserToken($userToken);
        $this->storeRefreshToken($refreshToken);
        $this->storeTokenExpires($expires);

        return $this;
    }

    /**
     * Clear the authentication tokens and userId.
     */
    public function logout(): void
    {
        $this->options->wipe();
    }

    /**
     * Clear the authentication tokens and userId, but keep the tracking
     * widget active so the website does not lose data.
     */
    public function logoutPreservingTracking(): void
    {
        $this->options->wipe(false, [
            TrackingScriptService::OPTION_TRACKING_HASH,
            TrackingScriptService::OPTION_TRACKING_ACTIVE,
        ]);
    }

    /**
     * Check if the client has all the necessary authentication tokens to show the dashboard.
     */
    public function hasAuthentication(): bool
    {
        return $this->hasUserToken() && $this->hasUserId();
    }

    /**
     * Build or return the configured HTTP client instance.
     */
    private function client(): Client
    {
        return new Client([
            'http_errors' => true,
            'expect' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => $this->getRequestUserAgent(),
            ]
        ]);
    }

    /**
     * Get the user agent string for the request.
     */
    public function getRequestUserAgent(): string
    {
        return "MetricoolPlugin/" . $this->env->getString('plugin.version') . " (WordPress/" . get_bloginfo('version') . "; PHP/" . phpversion() . "; ref: " . $this->getReferrer() . "; +" . site_url() . ")";
    }

    /**
     * EXTENDIFY_PARTNER_ID will contain the required value if WordPress is
     * configured using Extendify. Otherwise, use default 'wp'.
     */
    public function getReferrer(): string
    {
        return (defined('EXTENDIFY_PARTNER_ID') ? constant('EXTENDIFY_PARTNER_ID') : 'wp');
    }

    /**
     * Send a GET request.
     * @throws ApiException
     */
    public function get(string $endpoint): ?array
    {
        return $this->request('GET', $endpoint);
    }

    /**
     * Send a POST request.
     * @throws ApiException
     */
    public function post(string $endpoint, array $body): ?array
    {
        return $this->request('POST', $endpoint, $body);
    }

    /**
     * Send a PUT request.
     * @throws ApiException
     */
    public function put(string $endpoint, array $body): ?array
    {
        return $this->request('PUT', $endpoint, $body);
    }

    /**
     * Send a PATCH request.
     * @throws ApiException
     */
    public function patch(string $endpoint, array $body): ?array
    {
        return $this->request('PATCH', $endpoint, $body);
    }

    /**
     * Send a DELETE request.
     * @throws ApiException
     */
    public function delete(string $endpoint): ?array
    {
        return $this->request('DELETE', $endpoint);
    }

    /**
     * Send an authenticated request to the Metricool API.
     *
     * @param mixed|null $body
     * @throws ApiException
     */
    public function request(string $method, string $endpoint, $body = null): ?array
    {
        $this->validate();

        if ($this->isTokenExpired()) {
            $this->refreshAuthToken();
        }

        try {
            $response = $this->client->send(
                new Request($method, $this->formatUrl($endpoint), [
                    'Authorization' => 'Bearer ' . $this->getUserToken()
                ], json_encode($body))
            );
        } catch (Throwable $e) {
            throw new ApiException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }

        return $this->parseResponse($response);
    }

    /**
     * Exchange an OAuth authorization code for an access token.
     * @throws ApiException
     */
    public function exchangeOAuthCode(string $code, string $redirectUri): array
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $options = [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => $this->env->getString('metricool.oauth_client_id'),
                'code' => $code,
                'redirect_uri' => $redirectUri,
                'code_verifier' => 'login',
            ],
        ];

        try {
            $response = $this->client->send(
                new Request('POST', $this->env->getString('metricool.oauth_token_url'), $headers),
                $options
            );
        } catch (Throwable $e) {
            throw new ApiException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }

        $tokenData = $this->parseResponse($response);

        if (empty($tokenData['access_token']) || empty($tokenData['refresh_token']) || empty($tokenData['expires_in'])) {
            throw new ApiException('missing_token_data');
        }

        return $tokenData;
    }

    /**
     * Refresh the authentication token using the refresh token.
     *
     * Uses a MySQL lock to prevent concurrent processes from both
     * attempting a refresh. The process that cannot acquire the lock
     * waits in a loop until the token is refreshed by the lock holder.
     *
     * @throws ApiException when the refresh request fails or the
     *                       response is invalid.
     * @throws RuntimeException when polling times out.
     */
    public function refreshAuthToken(): void
    {
        $lockAcquired = $this->lockTokenRefresh();

        if ($lockAcquired === false) {
            $this->pollForNewUserToken();
            return;
        }

        try {
            $this->performTokenRefresh();
        } finally {
            $this->releaseRefreshLock();
        }
    }

    /**
     * Acquire a lock via wp_options to serialize token refresh attempts.
     * Uses INSERT IGNORE for atomicity: only one process can create the row.
     */
    private function lockTokenRefresh(): bool
    {
        global $wpdb;

        // Remove stale locks that might be left behind if a process crashes during refresh. We consider locks older than LOCK_STALE_MS as stale.
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name = %s AND option_value < %d",
                self::OPTION_REFRESH_LOCK,
                time() - $this->getRefreshLockTimeoutSeconds()
            )
        );

        // Attempt to insert the lock row. INSERT IGNORE ensures only one process succeeds when racing concurrently.
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $result = $wpdb->query(
            $wpdb->prepare(
                "INSERT IGNORE INTO {$wpdb->options} (option_name, option_value, autoload) VALUES (%s, %d, 'no')",
                self::OPTION_REFRESH_LOCK,
                time()
            )
        );

        return ($result !== false && $result > 0);
    }

    /**
     * Get the timeout duration of the token refresh request
     */
    private function getRefreshLockTimeoutSeconds(): int
    {
        return self::REFRESH_TIMEOUT_SECONDS + 1; // add 1 second buffer to account for request duration
    }

    /**
     * Wait for another process to refresh the token by polling if the token is expired
     * @throws RuntimeException if the token is still expired after waiting for the maximum time.
     */
    private function pollForNewUserToken(): void
    {
        $maxWaitMs = self::REFRESH_TIMEOUT_SECONDS * 1000;
        $sleepDurationMs = self::REFRESH_LOCK_WAIT_MS;
        $waitedMs = 0;

        while ($waitedMs < $maxWaitMs) {
            if ($this->isTokenExpired() === false) {
                $this->clearTokenOptionCache();
                return;
            }

            usleep($sleepDurationMs * 1000);
            $waitedMs += $sleepDurationMs;
        }

        throw new RuntimeException('Timed out waiting for token refresh. Please try again.');
    }

    /**
     * Remove the token options from the WordPress object cache so the
     * next read fetches the freshly refreshed values from the database.
     */
    private function clearTokenOptionCache(): void
    {
        wp_cache_delete(self::OPTION_AUTH_TOKEN, 'options');
        wp_cache_delete(self::OPTION_REFRESH_TOKEN, 'options');
        wp_cache_delete('alloptions', 'options');
    }


    /**
     * Perform the actual token refresh request against the
     * Metricool OAuth endpoint.
     *
     * @throws ApiException when the refresh request fails or the
     *                       response is invalid.
     */
    private function performTokenRefresh(): void
    {
        $refreshToken = $this->getRefreshToken();

        if (empty($refreshToken)) {
            $this->logoutPreservingTracking();
            throw new RuntimeException('No refresh token available, the user has been logged out.');
        }

        try {
            $options = [
                'form_params' => [
                    'client_id' => $this->env->getString('metricool.oauth_client_id'),
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $this->getRefreshToken(),
                ],
                'timeout' => self::REFRESH_TIMEOUT_SECONDS,
            ];

            $response = $this->client->send(
                new Request('POST', $this->env->getString('metricool.oauth_token_url'), [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]),
                $options
            );
        } catch (Throwable $e) {
            $this->logoutPreservingTracking();

            throw new ApiException(
                'Failed to refresh authentication token. Please log in again.',
                $e->getCode(),
                $e
            );
        }

        $data = $this->parseResponse($response);

        if (!isset($data['access_token'], $data['refresh_token'], $data['expires_in'])) {
            throw new ApiException('refresh_token response invalid.');
        }

        $this->storeUserToken($data['access_token']);
        $this->storeRefreshToken($data['refresh_token']);
        $this->storeTokenExpires($data['expires_in']);
    }

    /**
     * Release the wp_options lock after a token refresh.
     */
    private function releaseRefreshLock(): void
    {
        delete_option(self::OPTION_REFRESH_LOCK);
    }

    /**
     * Decode a JSON response body into an array.
     *
     * @throws ApiException when the response body is empty or
     *                      not valid JSON.
     */
    private function parseResponse(ResponseInterface $response): array
    {
        $response->getBody()->rewind();
        $decoded = json_decode($response->getBody()->getContents(), true);

        if (!is_array($decoded)) {
            throw new ApiException('Invalid JSON response from the API.');
        }

        return $decoded;
    }

    /**
     * Add userId and blogId to the URL as part of the authentication. When the
     * userId and blogId are not set, they will not be added to the URL, which
     * can still result in a successful request if the userToken is set and
     * valid.
     */
    private function formatUrl(string $url): string
    {
        $query = http_build_query(array_filter([
            'userId' => $this->getUserId(),
            'blogId' => $this->getBlogId(),
        ]));

        // Dirty hack to allow for non-standard query params
        // Metricool API supports urls with the same parameter multiple times
        // Example /v2/settings/users/:id?fields=alternativeEmail&fields=sendToAlternativeEmail
        $url = (strpos($url, '?') === false)
            ? $url . '?' . $query
            : $url . '&' . $query;

        return trailingslashit($this->apiUrl) . $url;
    }

    /**
     * Validate if all prerequisites are met to use the client. We need at least
     * the user token to be set before we can make any requests.
     * @throws InvalidArgumentException
     */
    public function validate(): void
    {
        $validationErrors = [];

        if ($this->hasAuthentication() === false) {
            $validationErrors[] = 'Authentication is required for Metricool API.';
        }

        if (!empty($validationErrors)) {
            throw new InvalidArgumentException(
                'Metricool Client is not setup correctly: ' . PHP_EOL .
                esc_html(implode(', ', $validationErrors))
            );
        }
    }
}
