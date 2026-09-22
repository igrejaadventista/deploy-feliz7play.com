<?php

declare(strict_types=1);

namespace Metricool\Features\Notifications\Notices;

use Metricool\Interfaces\NoticeInterface;

abstract class AbstractNotice implements NoticeInterface
{
    public const TYPE_INFO = 'info';
    public const TYPE_WARNING = 'warning';

    /**
     * This constant is used to show the notice on all routes.
     */
    public const ALL_ROUTES = '/';

    /**
     * Override this constant to define the identifier of the Notice. This
     * identifier is used to identify the Notice in the database and in the UI.
     */
    public const IDENTIFIER = '';

    /**
     * Override this property to define the version of the Notice. This version is
     * used to determine if the Notice should be upgraded during a plugin update.
     */
    protected string $version;

    /**
     * Use this property to define if the Notice is a premium Notice. This is used
     * as an alternative status when reading the Notice as an array. Useful for
     * the UI.
     */
    protected bool $premium;

    /**
     * Use this property to define if the Notice is active based on a
     * server-side condition. By default, a notice can activate based on a
     * client-side condition.
     */
    protected bool $active;

    /**
     * Override this method to define the title that should be displayed to the
     * user in the Notices dashboard component
     * @abstract
     */
    abstract public function getTitle(): string;

    /**
     * Override this method to define the text that should be displayed to the
     * user in the Notices dashboard component
     * @abstract
     */
    abstract public function getText(): string;

    /**
     * Use this method to define the route on which the Notice should be
     * displayed.
     * @abstract
     */
    abstract public function getRoute(): string;

    /**
     * @inheritDoc
     */
    public function setActive(bool $state = false): void
    {
        $this->active = $state;
    }

    /**
     * Override this method to set the notice as active based on a server-side
     * condition. By default, the notice is not active.
     */
    public function isActive(): bool
    {
        return $this->active ?? false;
    }

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
    public function getAction(): array
    {
        return [];
    }

    /**
     * Use this method to set the type of notice. By default, the type is
     * 'info' but you can override this method to set the type according to your
     * needs.
     */
    public function getType(): string
    {
        return self::TYPE_INFO;
    }

    /**
     * Reads if the Notice is premium
     */
    public function isPremium(): bool
    {
        return $this->premium ?? false;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'active' => $this->isActive(),
            'title' => $this->getTitle(),
            'text' => $this->getText(),
            'premium' => $this->isPremium(),
            'type' => $this->getType(),
            'route' => $this->getRoute(),
            'action' => $this->getAction(),
        ];
    }
}
