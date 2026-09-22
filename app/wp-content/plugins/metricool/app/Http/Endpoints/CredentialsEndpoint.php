<?php

declare(strict_types=1);

namespace Metricool\Http\Endpoints;

use Metricool\Http\Metricool\MetricoolApi;
use Metricool\Interfaces\SingleEndpointInterface;
use Metricool\Support\Validation\Validator;
use Metricool\Traits\HasAllowlistControl;
use Metricool\Traits\HasRestAccess;
use Throwable;

class CredentialsEndpoint implements SingleEndpointInterface
{
    use HasRestAccess;
    use HasAllowlistControl;

    public const ROUTE = 'credentials';

    public MetricoolApi $metricoolApi;

    public function __construct(MetricoolApi $metricoolApi)
    {
        $this->metricoolApi = $metricoolApi;
    }

    /**
     * @inheritDoc
     */
    public function registerRoute(): string
    {
        return self::ROUTE;
    }

    /**
     * @inheritDoc
     */
    public function enabled(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function registerArguments(): array
    {
        return [
            'methods' => \WP_REST_Server::EDITABLE,
            'callback' => [$this, 'callback'],
            'middleware' => ['metricool:auth'],
        ];
    }

    /**
     * Update the password
     *
     *     POST /wp-json/metricool/v1/credentials
     *     {
     *       "password": "current-password",
     *       "newPassword": "new-password"
     *     }
     */
    public function callback(\WP_REST_Request $request): \WP_REST_Response
    {
        $validated = Validator::validate($request->get_params(), [
            'password' => 'required|string',
            'newPassword' => 'required|string|confirm:password',
        ]);

        // Update the user password
        try {
            $this->metricoolApi->userCredentials()
                ->updatePassword($validated['password'], $validated['newPassword']);
        } catch (Throwable $e) {
            return $this->sendHttpErrorResponse(__('Something went wrong.', 'metricool'), $e->getMessage(), $e->getCode());
        }

        return $this->sendHttpResponse(['success' => true]);
    }
}
