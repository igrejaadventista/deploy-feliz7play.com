<?php

declare(strict_types=1);

namespace Metricool\Managers;

use Metricool\Bootstrap\App;
use Metricool\Exceptions\RestDataException;
use Metricool\Interfaces\MiddlewareInterface;
use Metricool\Interfaces\MultiEndpointInterface;
use Metricool\Interfaces\SingleEndpointInterface;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;
use Metricool\Support\Helpers\Storages\MiddlewareConfig;
use Metricool\Traits\HasAllowlistControl;
use Metricool\Traits\HasNonces;
use Metricool\Traits\HasRestAccess;
use Throwable;

final class EndpointManager extends AbstractManager
{
    use HasNonces;
    use HasAllowlistControl;
    use HasRestAccess;

    private EnvironmentConfig $env;

    private array $routes = [];
    private array $defaultMiddleware;
    private array $aliases;

    public function __construct(MiddlewareConfig $middleware, EnvironmentConfig $env)
    {
        $this->env = $env;
        $this->aliases = $middleware->get('aliases', []);
        $this->defaultMiddleware = $middleware->get('default_middleware', []);
    }

    /**
     * @inheritDoc
     */
    public function isRegistrable(object $class): bool
    {
        return ($class instanceof SingleEndpointInterface
            || $class instanceof MultiEndpointInterface
        );
    }

    /**
     * @inheritDoc
     */
    public function registerClass(object $class): void
    {
        if ($class instanceof SingleEndpointInterface) {
            $this->registerSingleEndpointRoute($class);
        }

        if ($class instanceof MultiEndpointInterface) {
            $this->registerMultiEndpointRoute($class);
        }
    }

    /**
     * @inheritDoc
     */
    public function afterRegister(): void
    {
        $this->registerWordPressRestRoutes();
        do_action('metricool_endpoints_loaded');
    }

    /**
     * Register a plugin route for and endpoint instance that implements the
     * {@see SingleEndpointInterface}
     */
    private function registerSingleEndpointRoute(SingleEndpointInterface $endpoint): void
    {
        if ($endpoint->enabled() === false) {
            return;
        }

        $this->routes[$endpoint->registerRoute()] = $endpoint->registerArguments();
    }

    /**
     * Register plugin routes for an endpoint instance that implements the
     * {@see MultiEndpointInterface}
     */
    private function registerMultiEndpointRoute(MultiEndpointInterface $endpoint): void
    {
        if ($endpoint->enabled() === false) {
            return;
        }

        $routeEndpoints = $endpoint->registerRoutes();
        foreach ($routeEndpoints as $route => $arguments) {
            $this->routes[$route] = $arguments;
        }
    }

    /**
     * This method provides a way to register custom REST routes via the
     * metricool_rest_routes filter. A controller or feature should be
     * instantiated before this manager is called and the controller should
     * hook into the metricool_rest_routes filter to add its own routes.
     *
     *      public function registerArguments(): array
     *      {
     *          return [
     *              'methods' => \WP_REST_Server::READABLE,
     *              'callback' => [$this, 'callback'],
     *              'permission_callback' => [$this, 'permissionCallback'],
     *              'middleware' => [
     *                  'metricool:auth', // alias in config/middleware.php
     *                  ExampleMiddleware::class, // MiddlewareInterface class
     *              ],
     *              'apply_default_middleware' => true, // optional, default is true
     *              'version' => 'v1', // optional, default is the value config/env.php
     *              'args' => [], // optional, args passed to register_rest_route
     *          ];
     *      }
     *
     * @uses apply_filters metricool_rest_routes
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    public function registerWordPressRestRoutes(): void
    {
        $routes = $this->getPluginRestRoutes();

        foreach ($routes as $route => $data) {
            $methods = ($data['methods'] ?? 'GET');
            $callback = ($data['callback'] ?? null);
            $permissionCallback = ($data['permission_callback'] ?? $this->defaultPermissionCallback());
            $middleware = ($data['middleware'] ?? []);
            $applyDefaultMiddleware = ($data['apply_default_middleware'] ?? true);
            $version = ($data['version'] ?? $this->env->getString('http.version'));
            $args = ($data['args'] ?? null);

            if (!is_callable($callback)) {
                throw new \InvalidArgumentException(
                    esc_html(sprintf('The callback for the route: %s is not callable.', $route))
                );
            }

            if ($applyDefaultMiddleware === true) {
                $middleware = array_merge($this->defaultMiddleware, $middleware);
            }

            $arguments = [
                'methods' => $this->normalizeMethods($methods),
                'callback' => $this->applyMiddleware($callback, $middleware),
                'permission_callback' => $permissionCallback,
            ];

            if ($args !== null) {
                $arguments['args'] = $args;
            }

            register_rest_route($this->env->getString('http.namespace') . '/' . $version, $route, $arguments);
        }
    }

    /**
     * The default permission callback applied to routes that do not define
     * their own. Default is ```metricool_manage``` capability check.
     */
    private function defaultPermissionCallback(): callable
    {
        return function (): bool {
            return $this->userCanManage();
        };
    }

    /**
     * Get the plugins REST routes
     * @uses apply_filters metricool_rest_routes
     */
    private function getPluginRestRoutes(): array
    {
        /**
         * Filter: metricool_rest_routes
         * Can be used to add or modify the REST routes
         *
         * @param array $routes
         * @return array
         * @example [
         *      'route' => [ // key is the route name
         *          'methods' => 'GET', // required
         *          'callback' => 'callback_function', // required
         *          'permission_callback' => 'permission_callback_function', // optional to override the default permission callback
         *          'version' => 'v1' // optional to override the default version
         *      ]
         * ]
         */
        return apply_filters('metricool_rest_routes', $this->routes);
    }

    /**
     * Wrap the endpoint callback with middleware. Provided middleware can either be an alias ```auth:metricool``` or
     * a FQCN```MetricoolAuthenticated::class```
     *
     * @param callable $callback The endpoint's callback
     * @param array $middlewares The middleware to apply
     * @return callable The wrapped callback
     *
     * @throws \ReflectionException
     */
    public function applyMiddleware(callable $callback, array $middlewares = []): callable
    {
        $instances = $this->resolveMiddleware($middlewares);
        $pipeline = $this->buildPipeline($callback, $instances);

        return function (\WP_REST_Request $request) use ($pipeline) {
            try {
                return $pipeline($request);
            } catch (RestDataException $e) {
                return $this->sendHttpErrorResponse($e->getMessage(), $e->getData(), $e->getResponseCode());
            } catch (Throwable $e) {
                return $this->sendHttpErrorResponse($e->getMessage(), null, $e->getCode() ?: 500);
            }
        };
    }

    /**
     * Return a pipeline of middleware into a single callback.
     */
    private function buildPipeline(callable $callback, array $middleware): callable
    {
        return array_reduce(
            array_reverse($middleware),
            static function (callable $next, MiddlewareInterface $middleware): callable {
                return static function (\WP_REST_Request $request) use ($middleware, $next) {
                    return $middleware->handle($request, $next);
                };
            },
            static function (\WP_REST_Request $request) use ($callback) {
                return $callback($request);
            }
        );
    }

    /**
     * Resolve middleware entries to MiddlewareInterface instances.
     *
     * @param string[] $middleware
     * @return MiddlewareInterface[]
     * @throws \ReflectionException
     */
    private function resolveMiddleware(array $middleware): array
    {
        $resolved = [];

        foreach ($middleware as $entry) {
            $class = $this->aliases[$entry] ?? $entry;

            if (!is_string($class) || !class_exists($class)) {
                throw new \InvalidArgumentException(
                    esc_html(sprintf("Middleware: %s could not be resolved to a valid class.", $entry))
                );
            }

            $instance = App::getInstance()->get($class);

            if (!$instance instanceof MiddlewareInterface) {
                throw new \InvalidArgumentException(
                    esc_html(sprintf('Middleware: %s must implement MiddlewareInterface.', $entry))
                );
            }

            $resolved[] = $instance;
        }

        return $resolved;
    }

    /**
     * Process the given methods and compare them to the allowed
     * {@see \WP_REST_Server::ALLMETHODS} methods. Remove unwanted entries and
     * cleanup method usage from, for example, "get " to "GET".
     *
     * @return string From "get, POSt, fake" to "GET,POST"
     */
    private function normalizeMethods(string $methods): string
    {
        // Split into array, trim whitespace and uppercase entries
        $methodsArray = array_map('trim', explode(',', $methods));
        $methodsArray = array_map('strtoupper', $methodsArray);

        // Split allowed entries into array and trim whitespaces
        $allowedMethodsArray = array_map('trim', explode(',', \WP_REST_Server::ALLMETHODS));

        // Keep only allowed methods
        $methodsArray = array_intersect($methodsArray, $allowedMethodsArray);
        $methodsArray = array_values(array_unique($methodsArray));

        // Convert back to CSV format for register_rest_route usage
        return implode(',', $methodsArray);
    }
}
