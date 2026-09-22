<?php

declare(strict_types=1);

namespace Metricool\Managers;

use Metricool\Interfaces\ControllerInterface;

final class ControllerManager extends AbstractManager
{
    /**
     * @inheritDoc
     */
    public function isRegistrable(object $class): bool
    {
        return $class instanceof ControllerInterface;
    }

    /**
     * @inheritDoc
     */
    public function registerClass(object $class): void
    {
        $class->register();
    }

    /**
     * @inheritDoc
     */
    public function afterRegister(): void
    {
        do_action('metricool_controllers_loaded');
    }
}
