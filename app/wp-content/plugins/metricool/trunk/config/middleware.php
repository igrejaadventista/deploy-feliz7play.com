<?php

use Metricool\Http\Middleware\HasMetricoolBlogId;
use Metricool\Http\Middleware\IsMetricoolAuthenticated;
use Metricool\Http\Middleware\IsWordPressAdminRequest;
use Metricool\Http\Middleware\SetLocale;
use Metricool\Http\Middleware\VerifyNonce;

return [
    'aliases' => [
        'locale' => SetLocale::class,
        'nonce' => VerifyNonce::class,
        'admin' => IsWordPressAdminRequest::class,
        'metricool:auth' => IsMetricoolAuthenticated::class,
        'metricool:blog_id' => HasMetricoolBlogId::class,
    ],
    // List of Middleware that should always be executed
    'default_middleware' => [
        'locale',
        'nonce',
    ],
];
