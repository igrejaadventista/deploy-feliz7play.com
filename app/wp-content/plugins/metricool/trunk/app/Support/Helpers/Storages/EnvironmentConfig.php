<?php

declare(strict_types=1);

namespace Metricool\Support\Helpers\Storages;

use Metricool\Support\Helpers\DeferredObject;
use Metricool\Support\Helpers\Storage;

/**
 * Environment configuration helper used in DI container.
 *
 * @mixin Storage This class acts as a proxy to Storage. All method calls are
 * resolved dynamically through {@see DeferredObject::__get()}
 */
final class EnvironmentConfig extends DeferredObject
{
    /**
     * @inheritDoc
     */
    protected function deferredClassString(): string
    {
        return Storage::class;
    }

    /**
     * @inheritDoc
     */
    protected function deferredConstructArguments(): array
    {
        return [
            'items' => $this->getStorageItems(),
        ];
    }

    /**
     * Method automatically resolves the environment configuration file.
     */
    private function getStorageItems(): array
    {
        $items = require dirname(__FILE__, 5) . '/config/env.php';

        return $this->overrideEnvironmentConfigItems($items);
    }

    /**
     * Developers can override rsp_auth_url with constant RSP_AUTH_URL and
     * base_api_domain with constant RSP_SB_BASE_API_DOMAIN. Set the constants
     * preferably in wp-config.php.
     *
     * Overrides values for:
     *
     *      $this->env->getUrl('metricool.rsp_auth_url')
     *      $this->env->getString('metricool.base_api_domain')
     */
    private function overrideEnvironmentConfigItems(array $items): array
    {
        if (defined('RSP_AUTH_URL')) {
            $items['metricool']['rsp_auth_url'] = constant('RSP_AUTH_URL');
        }

        if (defined('RSP_MC_BASE_API_DOMAIN')) {
            $items['metricool']['base_api_domain'] = constant('RSP_MC_BASE_API_DOMAIN');
        }

        if (defined('RSP_MC_OAUTH_CLIENT_ID')) {
            $items['metricool']['oauth_client_id'] = constant('RSP_MC_OAUTH_CLIENT_ID');
        }

        return $items;
    }
}
