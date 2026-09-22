<?php

if (!defined('ABSPATH')) {
    exit;
}

// The plugnis config can only be used AFTER or ON the 'init' hook.
return [
    'really-simple-ssl' => [
        'slug' => 'really-simple-ssl',
        'options_prefix' => 'rsssl',
        'activation_slug' => 'really-simple-ssl/rlrsssl-really-simple-ssl.php',
        'constant_free' => 'rsssl_version',
        'constant_premium' => 'rsssl_pro',
        'url' => 'https://wordpress.org/plugins/really-simple-ssl/',
        'upgrade_url' => 'https://really-simple-ssl.com/pro?src=metricool-plugin',
        'title' => "Really Simple Security - " . __("Lightweight plugin. Heavyweight security features.", "metricool"),
        'color' => '#f4bf3e',
    ],
    'simplybook' => [
        'slug' => 'simplybook',
        'options_prefix' => 'simplybook',
        'activation_slug' => 'simplybook/simplybook.php',
        'create' => admin_url('admin.php?page=simplybook-integration'),
        'url' => 'https://wordpress.org/plugins/simplybook/',
        'title' => 'SimplyBook.me - ' . __('Online Booking System', 'metricool'),
        'color' => '#06ADEF',
    ],
    'complianz-gdpr' => [
        'slug' => 'complianz-gdpr',
        'options_prefix' => 'cmplz',
        'activation_slug' => 'complianz-gdpr/complianz-gpdr.php',
        'constant_free' => 'cmplz_version',
        'constant_premium' => 'cmplz_premium',
        'create' => admin_url('admin.php?page=complianz'),
        'url' => 'https://wordpress.org/plugins/complianz-gdpr/',
        'upgrade_url' => 'https://complianz.io?src=metricool-plugin',
        'title' => 'Complianz - ' . __('Consent Management as it should be', 'metricool'),
        'color' => '#009fff',
    ],
    'complianz-terms-conditions' => [
        'slug' => 'complianz-terms-conditions',
        'options_prefix' => 'cmplz_tc',
        'activation_slug' => 'complianz-terms-conditions/complianz-terms-conditions.php',
        'constant_free' => 'cmplz_tc_version',
        'create' => admin_url('admin.php?page=terms-conditions'),
        'url' => 'https://wordpress.org/plugins/complianz-terms-conditions/',
        'upgrade_url' => 'https://complianz.io?metricool=cmplz-plugin',
        'title' => 'Complianz - ' . __("Terms & Conditions", "metricool"),
        'color' => '#000000',
    ],
];
