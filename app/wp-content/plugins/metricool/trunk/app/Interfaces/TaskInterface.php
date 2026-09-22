<?php

declare(strict_types=1);

namespace Metricool\Interfaces;

use Metricool\Features\TaskManagement\Exceptions\DismissRequiredTaskException;

interface TaskInterface
{
    /**
     * Returns the unique identifier of the task
     */
    public function getId(): string;

    /**
     * Returns the status of the task. For all available statuses
     * {@see AbstractTask} constants.
     */
    public function getStatus(): string;


    /**
     * Returns the priority of the task. A lower number is more important.
     */
    public function getPriority(): int;

    /**
     * Returns the version of the task
     */
    public function getVersion(): string;

    /**
     * Returns whether the task should be reactivated when the task is upgraded.
     * This is useful for tasks that are dismissed by the user but should be
     * reactivated when the task is upgraded to a new version.
     */
    public function isReactivateOnUpgrade(): bool;

    /**
     * Method is used to add an action to the UI of the task item.
     * @example
     * [
     *      'type' => 'button',
     *      'text' => 'Button text',
     *      'link' => 'https://example.com' | '/services/new,
     * ]
     * @return array
     */
    public function getAction(): array;

    /**
     * Returns all data needed to show the task in the UI. Keys that are
     * required are 'id', 'text', 'status', 'type' and 'action'.
     */
    public function toArray(): array;

    /**
     * Reads if the task is required
     */
    public function isRequired(): bool;

    /**
     * Reads if the task is hidden
     */
    public function isHidden(): bool;

    /**
     * Sets the status of the task from another task.
     */
    public function setStatusFromTask(TaskInterface $task): self;

    /**
     * Activate the task by setting the state to 'open'
     */
    public function open(): self;

    /**
     * Set the task to the 'urgent' state
     */
    public function urgent(): self;

    /**
     * Dismiss the task by setting the state to 'dismissed'
     * @throws DismissRequiredTaskException if the task is required
     */
    public function dismiss(): self;

    /**
     * Complete the task by setting the state to 'completed'
     */
    public function complete(): self;

    /**
     * Hide the task by setting the state to 'hidden'
     */
    public function hide(): self;
}
