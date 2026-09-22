<?php

declare(strict_types=1);

namespace Metricool\Managers;

use Metricool\Bootstrap\App;

abstract class AbstractManager
{
    /**
     * Overwrite this property to true when the entries that the child Manager
     * registers should be added to the container registry. For details see:
     * {@see App::make}
     */
    protected bool $useRegistry = false;

    /**
     * Overwrite this property to true when the dependencies of the entries that
     * the  child Manager registers should be added to the container registry.
     * For details see: {@see App::make}
     */
    protected bool $useRegistryForDependencies = true;

    /**
     * Child class should check if the given class can be registered. For
     * example by checking if it implements an interface to know the logic in
     * the {@see registerClass} method can be executed.
     */
    abstract public function isRegistrable(object $class): bool;

    /**
     * Logic to register the given class. If this method can be executed is
     * checked by the {@see isRegistrable} method.
     */
    abstract public function registerClass(object $class): void;

    /**
     * Method called after all classes given to the manager are registered.
     */
    abstract public function afterRegister(): void;

    /**
     * Register the given class as long as the entries are registrable according
     * to the child managers. Class are autowired, but not registered via
     * {@see App::make}
     *
     * @throws \LogicException When a developer is doing it wrong.
     * @throws \ReflectionException When the controller cannot be loaded.
     */
    public function register(array $classes): void
    {
        foreach ($classes as $fullyClassifiedName) {
            if (is_string($fullyClassifiedName) === false) {
                $type = gettype($fullyClassifiedName);
                throw new \LogicException(esc_html("Class must be a fully qualified name. Given type: $type"));
            }

            $class = App::getInstance()->make($fullyClassifiedName, $this->useRegistry, $this->useRegistryForDependencies);

            if ($this->isRegistrable($class) === false) {
                throw new \LogicException('Class is not registrable: ' . esc_html($fullyClassifiedName));
            }

            $this->registerClass($class);
        }

        $this->afterRegister();
    }
}
