<?php

declare(strict_types=1);

namespace Metricool\Features\TaskManagement;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Features\TaskManagement\Tasks\HistoricalDataTask;
use Metricool\Services\MetricoolAccountService;
use Metricool\Support\Helpers\Event;

class TaskManagementListener
{
    private TaskManagementService $service;
    private MetricoolAccountService $account;

    public function __construct(TaskManagementService $service, MetricoolAccountService $account)
    {
        $this->service = $service;
        $this->account = $account;
    }

    public function listen(): void
    {
        add_action('metricool_event_' . Event::POST_SCHEDULED, [$this, 'handlePostScheduled']);
        add_action('metricool_event_' . Event::CONNECTED_SOCIAL_NETWORKS_DATA_LOADED, [$this, 'handleSocialConnectedNetworks']);
        add_action('metricool_event_' . Event::METRICOOL_USER_UPDATED, [$this, 'handleMetricoolUserUpdate']);
    }

    /**
     * Handle the edit.php page load to check for tasks.
     */
    public function handlePostScheduled(): void
    {
        $this->service->completeTask(Tasks\SchedulePostTask::IDENTIFIER);
    }

    /**
     * This event receives the connected social networks data and completes or
     * opens tasks
     * @see Event::CONNECTED_SOCIAL_NETWORKS_DATA_LOADED
     */
    public function handleSocialConnectedNetworks(array $socialNetworks): void
    {
        // Complete or open the tasks that are tied to certain social networks
        $this->handleTasksForNetworks($socialNetworks);

        // Complete or open the FirstConnectionTask based on social network count
        if (count($socialNetworks) > 0) {
            $this->service->completeTask(Tasks\FirstConnectionTask::IDENTIFIER);
        } else {
            $this->service->openTask(Tasks\FirstConnectionTask::IDENTIFIER);
        }
    }

    /**
     * This event receives the active subscription data of the user and completes or opens tasks
     * based on the subscription data. For example, if the user has a premium subscription
     * @see Event::METRICOOL_USER_LOADED
     */
    public function handleMetricoolUserUpdate(array $user): void
    {
        $isPremium = $this->account->isPremium();

        // Complete or open the HistoricalDataTask based on subscription status
        if ($isPremium) {
            $this->service->completeTask(HistoricalDataTask::IDENTIFIER);
        } else {
            $this->service->openTask(HistoricalDataTask::IDENTIFIER);
        }
    }

    /**
     * Complete tasks for specific social networks
     */
    protected function handleTasksForNetworks(array $socialNetworks): void
    {
        // Map social network names to task identifiers
        $connectNetworkTasks = [
            'facebookData' => Tasks\TwitterTask::class,
            'linkedinData' => Tasks\LinkedInTask::class,
        ];

        // Check if any of the connected networks are associated with a task
        $foundNetworkTasks = array_intersect(array_keys($connectNetworkTasks), $socialNetworks);

        // Complete the task for the connected networks
        foreach ($foundNetworkTasks as $networkName) {
            $this->service->completeTask($connectNetworkTasks[$networkName]::IDENTIFIER);
        }

        // Open the tasks for the missing connected networks
        $missingNetworks = array_diff(array_keys($connectNetworkTasks), $foundNetworkTasks);
        foreach ($missingNetworks as $networkName) {
            $this->service->openTask($connectNetworkTasks[$networkName]::IDENTIFIER);
        }
    }
}
