<?php

declare(strict_types=1);

namespace Metricool\Features\TaskManagement;

use Metricool\Interfaces\TaskInterface;

class TaskManagementRepository
{
    public const OPTION_NAME = 'metricool_tasks';

    /** @var TaskInterface[] */
    private array $tasks = [];

    public function __construct()
    {
        $this->loadTasksFromDatabase();
    }

    /**
     * Retrieve a single task by its ID
     */
    public function getTask(string $taskId): ?TaskInterface
    {
        return $this->tasks[$taskId] ?? null;
    }

    /**
     * Retrieve all registered tasks
     * @return TaskInterface[]
     */
    public function getAllTasks(bool $strict = false): array
    {
        $tasks = $this->tasks;

        // If strict mode is enabled, remove tasks that are hidden
        if ($strict) {
            $tasks = array_filter($tasks, function ($task) {
                return $task->isHidden() === false;
            });
        }

        return $tasks;
    }

    /**
     * Adds or updates a single task to the repository
     */
    public function addTask(TaskInterface $task, bool $save = true): void
    {
        $this->tasks[$task->getId()] = $task;

        if ($save) {
            $this->saveTasksToDatabase();
        }
    }

    /**
     * Upgrade a task in the repository. Only replace existing tasks with same
     * identifier if the version is lower than the new task version.
     */
    public function upgradeTask(TaskInterface $task, bool $save = true): void
    {
        $existingTask = $this->getTask($task->getId());
        $taskExists = !empty($existingTask);

        $taskIsUpdatable = (
            !$taskExists
            || (version_compare($existingTask->getVersion(), $task->getVersion(), '<'))
        );

        if ($taskIsUpdatable === false) {
            return;
        }

        // Keep current status if new task does not want to reactivate on
        // upgrade and the existing task has a status
        if ($task->isReactivateOnUpgrade() === false) {
            $task->setStatusFromTask($existingTask);
        }

        // Upgrades existing tasks and add new tasks
        $this->addTask($task, $save);
    }

    /**
     * Remove a task from the repository
     */
    public function removeTask(TaskInterface $task, bool $save = true): void
    {
        unset($this->tasks[$task->getId()]);

        if ($save) {
            $this->saveTasksToDatabase();
        }
    }

    /**
     * Remove a task by its ID from the repository
     */
    public function removeTaskById(string $taskId, bool $save = true): void
    {
        if (isset($this->tasks[$taskId])) {
            unset($this->tasks[$taskId]);
        }

        if ($save) {
            $this->saveTasksToDatabase();
        }
    }

    /**
     * Load tasks from the WordPress database
     */
    private function loadTasksFromDatabase(): void
    {
        $storedTasks = get_option(self::OPTION_NAME, []);
        $this->tasks = is_array($storedTasks) ? $storedTasks : [];
    }

    /**
     * Save tasks to the WordPress database
     */
    public function saveTasksToDatabase(): void
    {
        update_option(self::OPTION_NAME, $this->tasks, false);
    }
}
