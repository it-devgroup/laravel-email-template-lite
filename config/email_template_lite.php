<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table name
    |--------------------------------------------------------------------------
    | Table name for email template
    */
    'table' => 'email_templates',

    /*
    |--------------------------------------------------------------------------
    | Model
    |--------------------------------------------------------------------------
    | Email template model (default): \ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate::class
    | Change to your custom class if you need to extend the model or change the table name
    | A custom class for email template must inherit the base class \ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate
    */
    'model' => \ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate::class,

    /*
    |--------------------------------------------------------------------------
    | Data from seeder file
    |--------------------------------------------------------------------------
    | Required
    | class - class seeder file (example): Database\Seeders\EmailTemplateTableSeeder::class
    | method - static method with array of data with seeder format
    | Example seeder file database/seeders/EmailTemplateTableSeeder.php:
    | class EmailTemplateTableSeeder extends Seeder
    | {
    | ...
    |     public static function data()
    |     {
    |         return [
    |             [
    |                'id' => 1,
    |                'type' => 'user_registration',
    |                ...
    |            ],
    |            [
    |                'id' => 2,
    |                'type' => 'user_banned',
    |                ...
    |            ],
    |            ...
    |        ];
    |    }
    | }
    */
    'data' => [
        'class' => '',
        'method' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Variable parser
    |--------------------------------------------------------------------------
    | Settings for an internal or external variable parser
    */
    'variable_parser' => [
        /*
        |--------------------------------------------------------------------------
        | Open and close tag for extract of variable
        |--------------------------------------------------------------------------
        */
        'tag_open' => '[[',
        'tag_close' => ']]',
        /*
        |--------------------------------------------------------------------------
        | Class and method for external variable parser
        |--------------------------------------------------------------------------
        | Default class: \ItDevgroup\LaravelEmailTemplateLite\Helpers\EmailTemplateVariableHelper::class
        | Default method: parse
        | Default method_type: static
        | Default method_set_variables: false
        | if "method" empty - class will be launched only specified the parser as follows:
        | $content = new CLASS(string $content, array $variables);
        |
        | if "method" not empty and "method_type" = public:
        | $content = (new CLASS(string $content, array $variables))->method();
        |
        | if "method" not empty and "method_type" = static:
        | $content = CLASS::method(string $content, array $variables);
        |
        | if "method" not empty, "method_type" = public and "method_set_variables" = true:
        | $content = (new CLASS())->method(string $content, array $variables);
        */
        'class' => \ItDevgroup\LaravelEmailTemplateLite\Helpers\EmailTemplateVariableHelper::class,
        'method' => 'parse',
        'method_type' => 'static',
        'method_set_variables' => false,
        /*
        |--------------------------------------------------------------------------
        | Fields for parsing
        |--------------------------------------------------------------------------
        | array [
        |     'subject',
        |     'body'
        | ]
        */
        'parse_fields' => [
            'subject',
            'body'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Config for sync command
    |--------------------------------------------------------------------------
    */
    'sync' => [
        /*
        |--------------------------------------------------------------------------
        | Creating new email template if not exists to database
        |--------------------------------------------------------------------------
        | true - create
        | false - not create
        */
        'create' => true,
        /*
        |--------------------------------------------------------------------------
        | Deleting email template if email template been deleted in seeder file
        |--------------------------------------------------------------------------
        | true - delete
        | false - not delete
        */
        'delete' => true,
        /*
        |--------------------------------------------------------------------------
        | List of fields which has being updated auto at sync
        |--------------------------------------------------------------------------
        | array [
        |     'field_1',
        |     'field_2'
        | ]
        */
        'update_fields' => [
        ]
    ],

    'variables' => [
        /*
        |--------------------------------------------------------------------------
        | Variables for all email template types
        |--------------------------------------------------------------------------
        | Example: [
        |     'common' => [
        |         'site_name' => \App\CustomEmailVariableSiteName::class,
        |         'site_url' => \App\CustomEmailVariableSiteUrl::class,
        |         ...
        |     ]
        | ]
        | Example class:
        | class CustomEmailTemplateVariableSiteName implements \ItDevgroup\LaravelEmailTemplateLite\EmailTemplateVariableInterface
        | {
        |     public function toString(): ?string
        |     {
        |         return 'text';
        |     }
        | }
        */
        'common' => [
            /* list of variables for all email template types */
        ],
        /*
        |--------------------------------------------------------------------------
        | Variables for email template by types
        |--------------------------------------------------------------------------
        | Example: [
        |     'type' => [
        |         'user_registration' => [
        |             'email',
        |             'password',
        |             ...
        |         ],
        |         ...
        |     ]
        | ]
        */
        'type' => [
            /* list of variables for email template by types */
        ]
    ]
];
