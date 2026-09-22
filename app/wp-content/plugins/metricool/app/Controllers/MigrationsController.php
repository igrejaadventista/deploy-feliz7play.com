<?php

declare(strict_types=1);

namespace Metricool\Controllers;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Interfaces\MigrationInterface;
use Metricool\Interfaces\ControllerInterface;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class MigrationsController implements ControllerInterface
{
    private EnvironmentConfig $env;
    private ?string $toVersion = null;
    private ?string $fromVersion = null;

    public function __construct(EnvironmentConfig $env)
    {
        $this->env = $env;
    }

    public function register(): void
    {
        add_action('metricool_plugin_version_upgrade', [$this, 'runMigrations'], 10, 2);
    }

    /**
     * Run the migrations that apply for the given version upgrade.
     */
    public function runMigrations(string $previousVersion, string $newVersion): void
    {
        $this->fromVersion = $previousVersion;
        $this->toVersion = $newVersion;

        $migrations = $this->getAllMigrations();

        foreach ($migrations as $migration) {
            $this->run($migration);
        }

        $this->afterMigrate();
    }

    /**
     * Run a single migration. Silently skips the migration if it does not fit
     * the version range.
     */
    private function run(MigrationInterface $migration): void
    {
        if ($this->shouldRunMigration($migration) === false) {
            return;
        }

        if ($this->isUpgrading()) {
            $migration->up();
        }
    }

    /**
     * Determine if a migration should run based on version comparison.
     *
     * When upgrading: run migration if version is between fromVersion and
     * toVersion or equal to toVersion. Makes sure up() is run when upgrading to
     * the exact version of the migration to apply all changes up to that
     * version.
     *
     * When downgrading: the version is less than fromVersion, but greater than
     * toVersion. Migrations cannot run in this case, because the current code
     * does not know about the changes that were made in future versions.
     *
     * @return bool True if migration should run
     * @throws \InvalidArgumentException When migration version is invalid
     */
    private function shouldRunMigration(MigrationInterface $migration): bool
    {
        if (version_compare($migration->version(), '0.0.1', '>=') === false) {
            throw new \InvalidArgumentException('Migration version must be a valid version number string.');
        }

        if ($this->isUpgrading()) {
            return version_compare($migration->version(), $this->fromVersion, '>')
                && version_compare($migration->version(), $this->toVersion, '<=');
        }

        // We cannot revert migrations from the future. Can you see the future?
        if ($this->isDowngrading()) {
            return false;
        }

        return false;
    }

    /**
     * Get all migration files from the migrations directory, sorted by version.
     * @return array<int, MigrationInterface>
     */
    private function getAllMigrations(): array
    {
        $migrationsPath = $this->env->getString('plugin.migrations_path');
        if (!is_dir($migrationsPath)) {
            return [];
        }

        $files = glob($migrationsPath . '*.php');
        if ($files === false) {
            return [];
        }

        $migrations = [];
        foreach ($files as $file) {
            $migration = require $file;
            $migrations[] = $migration;
        }

        // Sort migrations by version, lowest to highest
        usort($migrations, function ($a, $b) {
            return version_compare($a->version(), $b->version());
        });

        return $migrations;
    }

    /**
     * Determine if the migration is a downgrade. A downgrade occurs when
     * the toVersion is less than the fromVersion.
     * @throws \RuntimeException When developer is doing something wrong
     */
    private function isDowngrading(): bool
    {
        if ($this->fromVersion === null || $this->toVersion === null) {
            throw new \RuntimeException('From and To versions must be set before checking downgrade status.');
        }

        return version_compare($this->toVersion, $this->fromVersion, '<');
    }

    /**
     * Determine if the migration is an upgrade. An upgrade occurs when
     * the toVersion is greater than the fromVersion.
     * @throws \RuntimeException When developer is doing something wrong
     */
    private function isUpgrading(): bool
    {
        if ($this->fromVersion === null || $this->toVersion === null) {
            throw new \RuntimeException('From and To versions must be set before checking upgrade status.');
        }

        return version_compare($this->toVersion, $this->fromVersion, '>');
    }

    /**
     * Cleanup internal state after migrations have run.
     */
    private function cleanup(): void
    {
        $this->fromVersion = null;
        $this->toVersion = null;
    }

    /**
     * Actions to perform after all migrations have run. This includes
     * cleaning up internal state and firing an action hook.
     */
    private function afterMigrate(): void
    {
        $this->cleanup();
        do_action('metricool_migrations_run');
    }
}
