<?php

declare(strict_types=1);

namespace Metricool\Bootstrap;

/**
 * Container class that provides dependency injection capabilities to manage
 * object creation and resolution. Class is used for retrieving and injecting
 * dependencies in a structured and reusable way. This is important because it
 * decouples classes from concrete implementations (new..) and makes the
 * codebase easier to test and maintain.
 */
final class App
{
    /**
     * Singleton instance holder. Ensures a single container is shared across
     * the plugin without globals.
     */
    private static ?App $instance = null;

    /**
     * Registry of service factories indexed by identifier. Allows registering
     * lazy factory closures for services.
     * @var array<string, \Closure> $registry
     */
    private array $registry = [];

    /**
     * Instances of resolved services, indexed by identifier. Used to prevent
     * multiple instantiations and store created services.
     * @var array<string, object> $instances
     */
    private array $instances = [];

    /**
     * Private constructor to enforce the singleton pattern. Prevents direct
     * instantiation; use getInstance() instead.
     */
    private function __construct()
    {
    }

    /**
     * Retrieve the shared container instance. Provides a central access point
     * to the container for the plugin.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Register a service factory. Defers service creation until first use. The
     * provided Closure will be called with no arguments when the service is
     * resolved.
     */
    public function set(string $name, \Closure $value): void
    {
        $this->registry[$name] ??= $value;
    }

    /**
     * Resolve an identifier to a mixed instance. It first checks the registry
     * for a factory. If none is found, it calls {@see make} to perform
     * constructor autowiring.
     *
     * @param class-string $class
     * @return mixed
     * @throws \Exception If the target is not instantiable or cannot resolve a dependency.
     * @throws \ReflectionException If reflection fails.
     */
    public function get(string $class)
    {
        // Makes sure we memoize the Closure responses
        if (array_key_exists($class, $this->instances)) {
            return $this->instances[$class];
        }

        if (array_key_exists($class, $this->registry)) {
            $instance = ($this->registry[$class])();
            $this->instances[$class] = $instance; // See above
            return $instance;
        }

        return $this->make($class);
    }

    /**
     * Method is used for on-demand construction of an object using constructor
     * autowiring without touching the registry. When a constructor parameter
     * asks for the Container itself, the current Container instance is injected
     * instead of creating a new Container. Class-typed dependencies are
     * resolved via {@see get} so factories from the registry are honored, while
     * scalars or unresolved parameters require defaults or will result in an
     * exception. This keeps "make" safe for ad-hoc instances you may later
     * choose to register manually.
     *
     * @param class-string $class The class to make. Dependencies are injected.
     * @param bool $register Made classes are registered in the container on
     * true. Useful for optimization on multi-used classes.
     * @param bool $registerDependencies Made dependency classes are registered
     * in the container on true. Useful for optimization on multi-used classes.
     *
     * @throws \Exception If the target is not instantiable or a dependency cannot be resolved.
     * @throws \ReflectionException If reflection fails.
     */
    public function make(string $class, bool $register = true, bool $registerDependencies = true): object
    {
        $reflector = new \ReflectionClass($class);

        if ($reflector->isInstantiable() === false) {
            throw new \Exception(esc_html("Target [{$class}] is not instantiable."));
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $arguments = [];
        $parameters = $constructor->getParameters();

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            // No type hinted: allow default value, otherwise we cannot resolve.
            if ($type === null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $arguments[] = $parameter->getDefaultValue();
                    continue;
                }

                throw new \Exception(esc_html(sprintf(
                    'Cannot resolve untyped parameter $%s for [%s] without a default value.',
                    $parameter->getName(),
                    $class
                )));
            }

            // For PHP 7.4 only ReflectionNamedType exists (no unions).
            if ($type instanceof \ReflectionNamedType === false) {
                throw new \Exception(esc_html(sprintf(
                    'Unsupported parameter type for $%s in [%s].',
                    $parameter->getName(),
                    $class
                )));
            }

            // If nullable and no default, we still must supply something;
            if ($type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $arguments[] = $parameter->getDefaultValue();
                    continue;
                }

                throw new \Exception(esc_html(sprintf(
                    'Cannot autowire builtin parameter $%s (%s) for [%s]. Provide a default or register a factory.',
                    $parameter->getName(),
                    $type->getName(),
                    $class
                )));
            }

            /** @var class-string $dependencyClass */
            $dependencyClass = $type->getName();

            if ($dependencyClass === self::class) {
                throw new \Exception(esc_html(sprintf(
                    'Cannot resolve App container dependency for $%s in [%s] to prevent circular dependencies.',
                    $parameter->getName(),
                    $class
                )));
            }

            // Using get() will also resolve dependencies of dependencies
            $dependency = $this->get($dependencyClass);

            // Note: the registry is untouched here as that is only for deferred
            // factories (closures). The instances prop is used in get() too.
            if ($registerDependencies === true) {
                $this->instances[$dependencyClass] = $dependency;
            }

            $arguments[] = $dependency;
        }

        $made = new $class(...$arguments);

        // Note: the registry is untouched here as that is only for deferred
        // factories (closures). The instances prop is used in get() too.
        if ($register) {
            $this->instances[$class] = $made;
        }

        return $made;
    }
}
