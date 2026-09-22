<?php

declare(strict_types=1);

namespace Metricool\Features\TaskManagement\Tasks;

use Metricool\Interfaces\TaskInterface;
use Metricool\Features\TaskManagement\Exceptions\DismissRequiredTaskException;

abstract class AbstractTask implements TaskInterface
{
    public const DEFAULT_PRIORITY = 10;

    public const STATUS_URGENT = 'urgent';
    public const STATUS_OPEN = 'open';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_DISMISSED = 'dismissed';
    public const STATUS_HIDDEN = 'hidden';

    /**
     * Statuses that are used to determine the state of the task. A state holds
     * properties that are used for a task with this status.
     */
    protected const STATUS_PRIORITY = [
        self::STATUS_URGENT => 0,
        self::STATUS_OPEN => 10,
        self::STATUS_COMPLETED => 20,
        self::STATUS_DISMISSED => 30,
        self::STATUS_HIDDEN => 40,
    ];


    /**
     * Override this constant to define the identifier of the task. This
     * identifier is used to identify the task in the database and in the UI.
     */
    public const IDENTIFIER = '';

    /**
     * Override this property to define the version of the task. This version is
     * used to determine if the task should be upgraded during a plugin update.
     */
    protected string $version;

    /**
     * Override this property to define if the task is required or not. If the
     * task is required, the user will not be able to dismiss the task.
     */
    protected bool $required;

    /**
     * Override this property to define if the task should be reactivated when
     * the task is upgraded. This is useful for tasks that are dismissed by the
     * user but should be reactivated when the task is upgraded to a new
     * version.
     */
    protected bool $reactivateOnUpgrade;

    /**
     * Use this property to define if the task is a premium task. Useful for
     * the UI.
     */
    protected bool $premium;

    /**
     * Use this property to define if the task is related to a special feature
     * or not. Useful for the UI.
     */
    protected bool $specialFeature;

    /**
     * By default, a task is active on construct. This is because the $status
     * property is not set. The {@see getStatus()} method will therefore return
     * the default status 'open'. If you want to set a different default status
     * use the {@see setStatus()} method in the construct of the task. See
     * {@see AddMandatoryProviderTask} for an example.
     */
    protected string $status;

    /**
     * Override this method to define the text that should be displayed to the
     * user in the tasks dashboard component
     * @abstract
     */
    abstract public function getText(): string;

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return static::IDENTIFIER;
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return $this->version ?? '1.0.0';
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): string
    {
        return $this->status ?? self::STATUS_OPEN;
    }

    /**
     * @inheritDoc
     */
    public function getPriority(): int
    {
        // Get the priority from the status
        $priority = $this->getPriorityFromStatus();

        // Give premium and special features a higher priority
        if ($this->isPremium() || $this->isSpecialFeature()) {
            $priority++;
        }

        return $priority;
    }

    /**
     * Build the label for the task. This is used to display the task in the
     * tasks dashboard component. The label is used to indicate if the task
     * is premium or a special feature. If not, the label reflects the status.
     * Override with get{Status}Label method, for example: getUrgentLabel()
     */
    public function getLabel(): string
    {
        $status = $this->getStatus();

        // Get the label from method if it exists
        $getStatusLabelMethod = 'get' . ucfirst($status) . 'StatusLabel';
        if (method_exists($this, $getStatusLabelMethod)) {
            return $this->$getStatusLabelMethod();
        }

        return ucfirst($status);
    }

    /**
     * Get the label for the status 'open'
     */
    public function getOpenStatusLabel(): string
    {
        if ($this->isPremium()) {
            return __('Premium', 'metricool');
        }

        if ($this->isSpecialFeature()) {
            return __('Special feature', 'metricool');
        }

        return __('Open', 'metricool');
    }

    /**
     * Get the label for the status 'urgent'
     */
    public function getUrgentStatusLabel(): string
    {
        return __('Urgent', 'metricool');
    }

    /**
     * Get the label for the status 'completed'
     */
    public function getCompletedStatusLabel(): string
    {
        return __('Completed', 'metricool');
    }

    /**
     * Get the label for the status 'dismissed'
     */
    public function getDismissedStatusLabel(): string
    {
        return __('Dismissed', 'metricool');
    }

    /**
     * @inheritDoc
     */
    public function getAction(): array
    {
        return [];
    }

    /**
     * Sets the premium property of the task.
     */
    public function setPremium(bool $isPremium): void
    {
        $this->premium = $isPremium;
    }

    /**
     * Sets the special feature property of the task.
     */
    public function setSpecialFeature(bool $isSpecialFeature): void
    {
        $this->specialFeature = $isSpecialFeature;
    }

    /**
     * @inheritDoc
     */
    public function setStatusFromTask(TaskInterface $task): self
    {
        return $this->setStatus($task->getStatus());
    }

    /**
     * @inheritDoc
     */
    public function open(): self
    {
        return $this->setStatus(self::STATUS_OPEN);
    }

    /**
     * @inheritDoc
     */
    public function urgent(): self
    {
        return $this->setStatus(self::STATUS_URGENT);
    }

    /**
     * @inheritDoc
     */
    public function dismiss(): self
    {
        if (!$this->isDismissed() && $this->isRequired()) {
            throw new DismissRequiredTaskException();
        }

        return $this->setStatus(self::STATUS_DISMISSED);
    }

    /**
     * @inheritDoc
     */
    public function complete(): self
    {
        return $this->setStatus(self::STATUS_COMPLETED);
    }

    /**
     * @inheritDoc
     */
    public function hide(): self
    {
        return $this->setStatus(self::STATUS_HIDDEN);
    }

    /**
     * Reads if the task is completed
     */
    public function isCompleted(): bool
    {
        return $this->getStatus() === self::STATUS_COMPLETED;
    }

    /**
     * Reads if the task is dismissed
     */
    public function isDismissed(): bool
    {
        return $this->getStatus() === self::STATUS_DISMISSED;
    }

    /**
     * @inheritDoc
     */
    public function isHidden(): bool
    {
        return $this->getStatus() === self::STATUS_HIDDEN;
    }

    /**
     * @inheritDoc
     */
    public function isRequired(): bool
    {
        return $this->required ?? false;
    }

    /**
     * Reads if the task is premium
     */
    public function isPremium(): bool
    {
        return $this->premium ?? false;
    }

    /**
     * Reads if the task is related to a special feature
     */
    public function isSpecialFeature(): bool
    {
        return $this->specialFeature ?? false;
    }

    /**
     * @inheritDoc
     */
    public function isReactivateOnUpgrade(): bool
    {
        return $this->reactivateOnUpgrade ?? false;
    }

    /**
     * Sets the status of the task.
     */
    private function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Returns the priority of the task based on the status. If the status is
     * not found in STATUS_PRIORITY, the default priority is returned.
     */
    protected function getPriorityFromStatus(): int
    {
        $status = $this->getStatus();
        if (!isset(self::STATUS_PRIORITY[$status])) {
            return self::DEFAULT_PRIORITY;
        }
        return self::STATUS_PRIORITY[$status];
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
            'label' => $this->getLabel(),
            'status' => $this->getStatus(),
            'priority' => $this->getPriority(),
            'premium' => $this->isPremium(),
            'special_feature' => $this->isSpecialFeature(),
            'type' => $this->isRequired() ? 'required' : 'optional',
            'action' => $this->getAction(),
        ];
    }
}
