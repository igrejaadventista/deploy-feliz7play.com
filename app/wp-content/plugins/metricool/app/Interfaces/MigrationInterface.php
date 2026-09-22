<?php

declare(strict_types=1);

namespace Metricool\Interfaces;

interface MigrationInterface
{
    /**
     * Version to run this migration for.
     */
    public function version(): string;

    /**
     * Perform schema/data changes required for this migration. This method is
     * called from the code of the new version after an update. Make sure to
     * work within that context.
     */
    public function up(): void;
}
