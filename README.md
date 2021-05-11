## 
## Description

This package save and work with email template in to database

## Install for Lumen

**1.** Open file `bootstrap/app.php`

Uncommented strings

```
$app->withFacades();
$app->withEloquent();
```

Added after **$app->configure('app');**

```
$app->configure('email_template_lite');
```

add new service provider

```
$app->register(\ItDevgroup\LaravelEmailTemplateLite\Providers\EmailTemplateServiceProvider::class);
```

**2.** Run commands

For creating config file

```
php artisan email:template:publish --tag=config
```

For creating migration file

```
php artisan email:template:publish --tag=migration
```

For generate table

```
php artisan migrate
```

For creating resource file

```
php artisan email:template:publish --tag=resource
```

## Install for laravel

**1.** Open file **config/app.php** and search
```
    'providers' => [
        ...
    ]
```
Add to section
```
        \ItDevgroup\LaravelEmailTemplateLite\Providers\EmailTemplateServiceProvider::class,
```
Example
```
    'providers' => [
        ...
        \ItDevgroup\LaravelEmailTemplateLite\Providers\EmailTemplateServiceProvider::class,
    ]
```

**2.** Run commands

For creating config file

```
php artisan vendor:publish --provider="ItDevgroup\LaravelEmailTemplateLite\Providers\EmailTemplateServiceProvider" --tag=config
```

For creating migration file

```
php artisan email:template:publish --tag=migration
```

For generate table

```
php artisan migrate
```

For creating resource file

```
php artisan vendor:publish --provider="ItDevgroup\LaravelEmailTemplateLite\Providers\EmailTemplateServiceProvider" --tag=resources
```

## Next steps install for laravel and lumen

**1.** Create seeder file if not exists for email templates.
In the created seed file, you need to add a static method (for example, `public static function data()`).
The method must return an array of standard to fill the database

**2.** Open config file `config/email_template_lite.php` and add this class and method in exists parameters

```
'data' => [
    'class' => \Database\Seeders\EmailTemplateTableSeeder::class,
    'method' => 'data',
],
```

**3.** Setup section **variable_parser** for external or internal parser

## Command for sync email templates

```
php artisan email:template:sync
```

## Custom model

###### Step 1

Create custom model for email template

Example:

File: **app/CustomFile.php**

Content:

```
<?php

namespace App;

class CustomFile extends \ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate
{
}
```

If need change table name or need added other code:

```
<?php

namespace App;

class CustomFile extends \ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate
{
    protected $table = 'YOUR_TABLE_NAME';
    
    // other code
}
```

###### Step 2

Open **config/email_template_lite.php** and change parameter "model", example:

```
...
// replace
'model' => \ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate::class,
// to
'model' => \App\CustomFile::class,
```

###### Step 3

Use custom **\App\CustomFile** model everywhere instead of standard model **\ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate**

## Usage

#### Initialize service

```
$service = app(\ItDevgroup\LaravelEmailTemplateLite\EmailTemplateServiceInterface::class);
```

or injected

```
// use
use ItDevgroup\LaravelEmailTemplateLite\EmailTemplateServiceInterface;
// constructor
public function __construct(
    EmailTemplateServiceInterface $emailTemplateService
)
```

further we will use the variable **$service**

#### List of email templates

All email templates

```
$eloquentCollection = $service->getList();
```

Email templates with filter. All filter parameters not required

```
$filter = (new \ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplateFilter())
    ->setIsPublic(true);
    
// or

$filter = (new \ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplateFilter())
    ->setType('type_name_by_like')
    ->setTitle('title_by_like')
    ->setIsActive(true);
$eloquentCollection = $service->getList($filter);
```

Email templates with pagination

```
$lengthAwarePaginator = $service->getList(null, $page, $perPage);
$lengthAwarePaginator = $service->getList(null, 1, 10);
```

Email templates with sorting

```
$eloquentCollection = $service->getList(null, null, null, $fieldName, $ascOrDesc);
$eloquentCollection = $service->getList(null, null, null, 'title', 'ASC');
```

#### Email template by ID

```
$emailTemplate = $service->getById(1);
```

#### Email template by TYPE

```
$emailTemplate = $service->getByType('emailTemplate_type');
```

#### Email template create

```
$emailTemplate = \ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate::register(
    'type',
    'Title',
    'Subject',
    'Body'
);
$emailTemplate->is_active = true;
$service->createModel($emailTemplate);
```

#### Email template update

```
$emailTemplate = $service->getById(1);
$emailTemplate->title = 'Title';
$emailTemplate->subject = 'Subject';
$emailTemplate->body = 'Body';
$emailTemplate->is_active = true;
$service->updateModel($emailTemplate);
```

#### Email template delete

```
$emailTemplate = $service->getById(1);
$service->deleteModel($emailTemplate);
```

#### Email template parse short codes

```
$emailTemplate = $service->getById(1);
// or
$emailTemplate = $service->getByType('type');
$service->render($emailTemplate, ['test_1' => '111', 'test_2' => 222]);
return $emailTemplate;
```

#### Email template variables

```
$emailTemplate = $service->getById(1);
// or
$emailTemplate = $service->getByType('type');
$emailTemplate->variables; // Collection
$emailTemplate->variables[0]->key;
$emailTemplate->variables[0]->description;
```

## The procedure of adding new template

1. Add to seeder file

2. Run sync command

```
php artisan email:template:sync
```

3. Add email template variables to config file **config/email_template_lite.php** in section **variables**

4. Add text for variables in to lexicon file **resources/lang/LANG_KEY/email_template_variables.php**

## The procedure of adding new common variable

1. Open file **config/email_template_lite.php** and add variable in section **variables - common**, example:

```
'variables' => [
    'common' => [
        'site_name' => \App\CustomEmailTemplateVariableSiteName::class,
    ],
    ...
```

2. Create class **\App\CustomEmailTemplateVariableSiteName**

3. The class must be an implementation of the interface **ItDevgroup\LaravelEmailTemplateLite\EmailTemplateVariableInterface**

4. The class must contain a public method **toString(): ?string**

Full example file

```
<?php

namespace App;

use ItDevgroup\LaravelEmailTemplateLite\EmailTemplateVariableInterface;

class CustomEmailTemplateVariableSiteName implements EmailTemplateVariableInterface
{
    public function toString(): ?string
    {
        return 'site name';
    }
}
```
