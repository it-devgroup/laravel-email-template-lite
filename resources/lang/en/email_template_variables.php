<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Text for all email template types
    |--------------------------------------------------------------------------
    */
    'common' => [
        'site_name' => 'Site name'
        /* other variables */
    ],
    /*
    |--------------------------------------------------------------------------
    | Text for email template type "custom_type" and other types
    |--------------------------------------------------------------------------
    | Variables specified here take precedence over those specified in the section above "common"
    */
    'type' => [
        'custom_type' => [
            'site_name' => 'Site name'
        ],
        'custom_type_2' => [
            'site_name' => 'Site name 2',
            'other_variable' => 'Variable 2',
        ]
        /* other types */
    ]
];
