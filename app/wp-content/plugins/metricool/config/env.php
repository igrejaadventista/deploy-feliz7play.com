<?php

if (!defined('ABSPATH')) {
    exit;
}

// The environment config can be used BEFORE the 'init' hook.
return [
    'plugin' => [
        'name' => 'Metricool',
        'version' => '2.1.0',
        'pro' => true,
        'path' => dirname(__DIR__),
        'base_path' => dirname(__DIR__) . DIRECTORY_SEPARATOR . plugin_basename(dirname(__DIR__)) . '.php',
        'assets_path' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR,
        'lang_path' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR,
        'view_path' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR,
        'feature_path' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Features' . DIRECTORY_SEPARATOR,
        'migrations_path' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR,
        'react_path' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'react',
        'dir' => plugin_basename(dirname(__DIR__)),
        'base_file' => plugin_basename(dirname(__DIR__)) . '/' . plugin_basename(dirname(__DIR__)) . '.php',
        'lang' => plugin_basename(dirname(__DIR__)) . '/assets/languages',
        'url' => plugin_dir_url(__DIR__),
        'assets_url' => plugin_dir_url(__DIR__) . 'assets/',
        'views_url' => plugin_dir_url(__DIR__) . 'views/',
        'react_url' => plugin_dir_url(__DIR__) . 'react',
        'dashboard_url' => admin_url('admin.php?page=metricool'),
        'support_url' => 'https://wordpress.org/support/plugin/metricool/',
        'review_url' => 'https://wordpress.org/support/plugin/metricool/reviews/#new-post',
    ],
    'metricool' => [
        'rsp_auth_url' => 'https://metricool.rsp-auth.com',
        'base_api_domain' => 'https://app.metricool.com/api',
        'base_url' => 'https://app.metricool.com/',
        'help_url' => 'https://help.metricool.com',
        'upgrade_premium_url' => 'https://app.metricool.com/user-settings/plan',
        'connect_network_url' => 'https://app.metricool.com/evolution/brandSummary',
        'connect_linkedin_url' => 'https://app.metricool.com/evolution/linkedin',
        'connect_twitter_url' => 'https://app.metricool.com/evolution/twitter',
        'tracking_script_url' => 'https://tracker.metricool.com/resources/be.js',
        'create_post_url' => 'https://app.metricool.com/planner/post',
        'oauth_authorize_url' => 'https://app.metricool.com/oauth/authorize',
        'oauth_token_url' => 'https://app.metricool.com/oauth/token',
        'oauth_client_id' => 'BaKuXnUZBvNvNHrNXtGivVxwnfGKitgc',
        'google_recaptcha_key' => '6LflMV4sAAAAAMyPohHfMRVjZQBcu-YuZz_3nTTK',
    ],
    'http' => [
        'version' => 'v1',
        'namespace' => 'metricool',
    ],
    'frontend' => [
        'trusted_urls' => [
            'legal_terms' => 'https://metricool.com/legal-terms/',
            'new_support_ticket' => 'https://wordpress.org/support/plugin/metricool/#new-topic-0',
            'base_url' => 'https://app.metricool.com/',
        ],
    ]
];
