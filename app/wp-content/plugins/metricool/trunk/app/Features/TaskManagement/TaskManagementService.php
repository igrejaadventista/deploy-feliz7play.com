<?php

declare(strict_types=1);

namespace Metricool\Features\TaskManagement;

use Metricool\Bootstrap\App;
use Metricool\Interfaces\TaskInterface;

class TaskManagementService
{
    private TaskManagementRepository $repository;

    public function __construct(TaskManagementRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Check if there are tasks
     */
    public function hasTasks(): bool
    {
        return !empty($this->repository->getAllTasks());
    }

    /**
     * Get a task by its identifier
     */
    public function getTask(string $taskId): ?TaskInterface
    {
        return $this->repository->getTask($taskId);
    }

    /**
     * Get all tasks
     * @return TaskInterface[]
     */
    public function getAllTasks(bool $strict = false): array
    {
        return $this->repository->getAllTasks($strict);
    }

    /**
     * Add or update a task
     */
    public function addTask(TaskInterface $task, bool $save = true): void
    {
        $this->repository->addTask($task, $save);
    }

    /**
     * Add or update multiple tasks at once
     * @param class-string<TaskInterface>[] $tasks
     * @throws \Exception If task class cannot be instantiated
     */
    public function addTasks(array $tasks): void
    {
        foreach ($tasks as $taskClassString) {
            $task = App::getInstance()->make($taskClassString);

            $this->repository->addTask($task, false);
        }
        $this->repository->saveTasksToDatabase();
    }

    /**
     * Upgrade the tasks. Only replace existing tasks with same identifier if
     * the version is lower than the new task version. Add missing tasks and
     * remove tasks that are no longer present.
     * @param class-string<TaskInterface>[] $tasks
     * @throws \Exception If task class cannot be instantiated
     */
    public function upgradeTasks(array $tasks): void
    {
        // Remove tasks that are no longer present. Maybe that are them all?
        $deletableTasksList = $this->repository->getAllTasks();

        foreach ($tasks as $taskClassString) {
            $task = App::getInstance()->make($taskClassString);

            $this->repository->upgradeTask($task, false);

            // Current tasks is not deletable so remove it from the list
            unset($deletableTasksList[$task->getId()]);
        }

        // If list still contains tasks, the upgrade requests them to be removed
        if (!empty($deletableTasksList)) {
            $this->removeDeletableTasksAfterUpgrade($deletableTasksList, false);
        }

        $this->repository->saveTasksToDatabase();
    }

    /**
     * Open a task in the repository
     */
    public function openTask(string $taskId): void
    {
        $task = $this->getTask($taskId);
        if ($task) {
            $this->addTask($task->open());
        }
    }

    /**
     * Dismiss a task
     */
    public function dismissTask(string $taskId): void
    {
        $task = $this->getTask($taskId);
        if ($task) {
            $this->addTask($task->dismiss());
        }
    }

    /**
     * Complete a task
     */
    public function completeTask(string $taskId): void
    {
        $task = $this->getTask($taskId);
        if ($task) {
            $this->addTask($task->complete());
        }
    }

    /**
     * Hide a task
     */
    public function hideTask(string $taskId): void
    {
        $task = $this->getTask($taskId);
        if ($task) {
            $this->addTask($task->hide());
        }
    }

    /**
     * Flag a task as urgent
     */
    public function flagTaskUrgent(string $taskId): void
    {
        $task = $this->getTask($taskId);
        if ($task) {
            $this->addTask($task->urgent());
        }
    }

    /**
     * Remove multiple tasks at once
     * @param TaskInterface[] $tasks
     */
    public function removeTasks(array $tasks, bool $save = true): void
    {
        foreach ($tasks as $task) {
            $this->repository->removeTask($task, $save);
        }

        if ($save) {
            $this->repository->saveTasksToDatabase();
        }
    }

    /**
     * Remove tasks that are no longer present in our Task Object list. Such
     * tasks are now a __PHP_Incomplete_Class and do not implement the
     * TaskInterface. Because of this we cannot use the task classes.
     */
    private function removeDeletableTasksAfterUpgrade(array $deletableTasksList, bool $save = true): void
    {
        foreach ($deletableTasksList as $taskId => $deletedTask) {
            $this->repository->removeTaskById($taskId, $save);
        }

        if ($save) {
            $this->repository->saveTasksToDatabase();
        }
    }
}
