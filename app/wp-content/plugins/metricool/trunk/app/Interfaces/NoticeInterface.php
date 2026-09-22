<?php

declare(strict_types=1);

namespace Metricool\Interfaces;

interface NoticeInterface
{
    /**
     * Returns the unique identifier of the notice
     */
    public function getId(): string;

    /**
     * Returns the version of the notice
     */
    public function getVersion(): string;

    /**
     * Method is used to add a link to the UI of the notice item.
     * Example (normal link):
     *  [
     *       'text' => 'Link text',
     *       'link' => 'https://example.com' | '/services/new,
     *  ]
     * Example (login link):
     * [
     *      'text' => 'Link text',
     *      'login_link' => '/v2/management/',
     * ]
     */
    public function getAction(): array;

    /**
     * Returns all data needed to show the notice in the UI. Keys that are
     * required are 'id', 'text', 'status', 'type' and 'action'.
     */
    public function toArray(): array;

    /**
     * Use this method to set the notice as active based on a server-side
     * condition. By default, a notice can activate based on a client-side
     * condition.
     */
    public function setActive(bool $state = false): void;
}
