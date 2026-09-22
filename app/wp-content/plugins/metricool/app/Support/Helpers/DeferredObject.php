<?php

declare(strict_types=1);

namespace Metricool\Support\Helpers;

/**
 * Deferred object helper class to delay the instantiation of a class until
 * one of its methods is called. See for example {@see GeneralConfig}.
 */
abstract class DeferredObject
{
    /**
     * Return the class-string of the class you want to instantiate on the first
     * method call.
     * @return class-string
     */
    abstract protected function deferredClassString(): string;

    /**
     * The key should reflect the constructor argument for the deferred class.
     * @return array
     */
    abstract protected function deferredConstructArguments(): array;

    /**
     * Cache a single deferred instance. This class intentionally only stores
     * one instance. Call {@see clearDeferredInstance} when you need to force a
     * re-instantiation (for example, when the constructor inputs have changed).
     * @var object|null
     */
    private ?object $deferredInstance = null;

    /**
     * Clear the cached deferred instance. Useful when the construction inputs
     * change during runtime, and you want to force a re-instantiation.
     */
    protected function clearDeferredInstance(): void
    {
        $this->deferredInstance = null;
    }

    /**
     * Retrieve the cached instance or create a new one and cache it.
     *
     * Note: this method only caches a single instance. If you need to change
     * the underlying construction inputs, call {@see clearDeferredInstance}
     * to force recreation.
     *
     * @throws \ReflectionException
     */
    private function getDeferredInstance(): object
    {
        if ($this->deferredInstance !== null) {
            return $this->deferredInstance;
        }

        $classString = $this->deferredClassString();
        $constructArgs = $this->deferredConstructArguments();

        $reflectionClass = new \ReflectionClass($classString);
        $instance = $reflectionClass->newInstanceArgs($constructArgs);

        $this->deferredInstance = $instance;

        return $instance;
    }

    /**
     * Magic method to forward method calls to the deferred instance.
     * @return mixed
     * @throws \ReflectionException
     */
    public function __call(string $name, array $arguments)
    {
        $instance = $this->getDeferredInstance();

        // If the method is public and callable, call it directly for speed.
        if (is_callable([$instance, $name])) {
            return $instance->$name(...$arguments);
        }

        $reflectionClass = new \ReflectionClass($instance);

        $method = $reflectionClass->getMethod($name);
        return $method->invokeArgs($instance, $arguments);
    }
}
