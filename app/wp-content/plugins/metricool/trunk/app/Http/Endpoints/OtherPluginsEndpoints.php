<?php

declare(strict_types=1);

namespace Metricool\Http\Endpoints;

use Metricool\Interfaces\MultiEndpointInterface;
use Metricool\Services\OtherPluginService;
use Metricool\Support\Helpers\Collection;
use Metricool\Support\Helpers\Storages\GeneralConfig;
use Metricool\Traits\HasAllowlistControl;
use Metricool\Traits\HasRestAccess;

class OtherPluginsEndpoints implements MultiEndpointInterface
{
    use HasRestAccess;
    use HasAllowlistControl;

    private GeneralConfig $config;
    private OtherPluginService $service;

    public function __construct(GeneralConfig $config, OtherPluginService $service)
    {
        $this->config = $config;
        $this->service = $service;
    }

    /**
     * @inheritDoc
     */
    public function registerRoutes(): array
    {
        return [
            'other_plugins_data' => [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'getOtherPluginsData'],
            ],
            'do_plugin_action' => [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [$this, 'doOtherPluginAction'],
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function enabled(): bool
    {
        return true;
    }

    /**
     * Get plugin data for other plugin section
     *
     *     GET /wp-json/metricool/v1/other_plugins_data
     */
    public function getOtherPluginsData(\WP_REST_Request $request): \WP_REST_Response
    {
        $plugins = $this->buildOtherPluginData();
        return $this->sendHttpResponse([
            'plugins' => $plugins
        ]);
    }

    /**
     * Perform an action on a plugin
     *
     *     POST /wp-json/metricool/v1/do_plugin_action
     *     {
     *         "slug": "really-simple-ssl",
     *         "action": "download"
     *     }
     */
    public function doOtherPluginAction(\WP_REST_Request $request): \WP_REST_Response
    {
        $storage = $this->retrieveHttpStorage($request);

        $slug = $storage->getString('slug', 'really-simple-ssl');
        $action = $storage->getString('action', 'download');

        $plugins = $this->buildOtherPluginData($slug);
        $plugin = reset($plugins);

        if (empty($plugin)) {
            return $this->sendHttpErrorResponse(__('Plugin not found', 'metricool'));
        }

        $this->service->setPluginConfig($plugin);

        try {
            $this->service->executeAction($action);
        } catch (\Exception $e) {
            return $this->sendHttpErrorResponse(
                __('An error occurred while performing the action.', 'metricool'),
                $e->getMessage(),
                $e->getCode()
            );
        }

        // After executing the action, a new action should be available
        $plugin['action'] = $this->service->getAvailablePluginAction();

        return $this->sendHttpResponse([
            'plugin' => $plugin
        ]);
    }

    /**
     * Get other plugins from the other config and manipulate the array
     * with the Installer class.
     * @param string $targetPluginSlug Can be used to filter the plugins array
     * for a specific plugin entry based on the slug key.
     */
    public function buildOtherPluginData(string $targetPluginSlug = ''): array
    {
        $plugins = new Collection($this->config->get('plugins'));

        if (!empty($targetPluginSlug)) {
            $plugins = $plugins->filter(function ($plugin) use ($targetPluginSlug) {
                return isset($plugin['slug']) && $plugin['slug'] === $targetPluginSlug;
            });
        }

        $plugins = $plugins->map(function ($plugin) {
            $this->service->setPluginConfig($plugin);

            $plugin['url'] = $this->service->getPluginUrl();
            $plugin['action'] = $this->service->getAvailablePluginAction();
            $plugin['active'] = $this->service->getPluginActive();

            return $plugin;
        });

        return $plugins->where('active', '==', false)
            ->take(3)
            ->toArray();
    }
}
