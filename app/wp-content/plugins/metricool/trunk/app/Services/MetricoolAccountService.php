<?php

declare(strict_types=1);

namespace Metricool\Services;

use Metricool\Http\Metricool\MetricoolApi;
use Metricool\Support\Helpers\Event;
use Throwable;

/**
 * Service that is responsible for getting the account information
 * (premium, user details, etc) of the user that is currently authenticated
 * with the Metricool API
 */
class MetricoolAccountService
{
    public const METRICOOL_USER_OPTION = 'metricool_user';

    private ?array $user;
    private MetricoolApi $metricool;

    public function __construct(MetricoolApi $metricool)
    {
        $this->metricool = $metricool;
        $this->user = get_option(self::METRICOOL_USER_OPTION, null);
    }

    /**
     * Attempts to fetch account from LeadInfo, return the stale data on error
     * @return array Account details from api
     * @uses EVENT::METRICOOL_USER_UPDATED
     */
    public function fetch(): array
    {
        try {
            $user = $this->metricool->user()->get();
        } catch (Throwable $e) {
            return $this->accountData();
        }

        $this->storeUser($user);

        Event::dispatch(EVENT::METRICOOL_USER_UPDATED, $user);

        return $this->accountData();
    }

    /**
     * Return the Metricool user
     */
    public function getUser(): ?array
    {
        return $this->user;
    }

    /**
     * Stores the Metricool user in the database
     */
    public function storeUser(array $user): void
    {
        $this->user = $user;
        update_option(self::METRICOOL_USER_OPTION, $user, false);
    }

    /**
     * Returns if the account is a paid / premium account
     */
    public function isPremium(): bool
    {
        return isset($this->user['subscription']['plan']['id'])
            && $this->user['subscription']['plan']['id'] !== 'FREE';
    }

    /**
     * Returns a serialized version of the account of this user to be used in the front-end
     */
    public function accountData(): array
    {
        return [
            'user_id' => $this->metricool->getUserId(),
            'blog_id' => $this->metricool->getBlogId(),
            'is_premium' => $this->isPremium(),
            'user' => $this->getUser(),
        ];
    }
}
