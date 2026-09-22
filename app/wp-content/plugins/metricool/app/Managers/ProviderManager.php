<?php

declare(strict_types=1);

namespace Metricool\Managers;

use Metricool\Interfaces\ProviderInterface;

final class ProviderManager extends AbstractManager
{
    /**
     * @inheritDoc
     */
    public function isRegistrable(object $class): bool
    {
        return $class instanceof ProviderInterface;
    }

    /**
     * @inheritDoc
     */
    public function registerClass(object $class): void
    {
        $class->provide();
    }

    /**
     * @inheritDoc
     */
    public function afterRegister(): void
    {
        do_action('metricool_providers_loaded');
    }
}
