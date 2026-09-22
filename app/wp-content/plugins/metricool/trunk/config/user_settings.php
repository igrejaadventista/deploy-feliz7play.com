<?php

use Metricool\Features\UserSettings\Rules\FirstDayOfWeekRule;
use Metricool\Features\UserSettings\Rules\SupportedLanguageRule;
use Metricool\Features\UserSettings\Rules\TimezoneRule;

return [
    'fields' => [
        'blogId' => [
            'storage' => 'default',
            'validators' => ['string'],
        ],
        'name' => [
            'section' => 'personal',
            'storage' => 'metricool',
            'validators' => ['required', 'string'],
        ],
        'lastName' => [
            'section' => 'personal',
            'storage' => 'metricool',
            'validators' => ['string'],
        ],
        'language' => [
            'section' => 'preferences',
            'storage' => 'metricool',
            'validators' => [SupportedLanguageRule::class],
        ],
        'timezone' => [
            'section' => 'preferences',
            'storage' => 'metricool',
            'validators' => [TimezoneRule::class],
        ],
        'firstDayOfTheWeek' => [
            'section' => 'preferences',
            'storage' => 'metricool',
            'validators' => [FirstDayOfWeekRule::class],
        ],
        'sendToAlternativeEmail' => [
            'section' => 'account',
            'validators' => ['required', 'boolean'],
            'type' => 'boolean',
            'storage' => 'metricool',
        ],
        'alternativeEmail' => [
            'section' => 'account',
            'validators' => ['requiredIf:sendToAlternativeEmail,true', 'email'],
            'storage' => 'metricool',
        ],
        'trackingScriptActive' => [
            'section' => 'tracking',
            'type' => 'boolean',
            'defaultValue' => false,
            'validators' => ['boolean'],
        ],
        'trackingScriptHash' => [
            'section' => 'tracking',
            'validators' => ['string'],
        ]
    ],
    'storages' => [
        'default' => [
            'class' => 'OptionsStorage',
            'prefix' => 'metricool_',
            'casing' => 'snakeCase',
        ],
        'metricool' => [
            'class' => 'RemoteStorage',
            'method' => 'patch',
            'casing' => 'camelCase',
        ],
    ],
];
