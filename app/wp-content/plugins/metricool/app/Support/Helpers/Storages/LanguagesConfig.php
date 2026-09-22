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
final class LanguagesConfig extends DeferredObject
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
        return require dirname(__FILE__, 5) . '/config/languages.php';
    }
}
