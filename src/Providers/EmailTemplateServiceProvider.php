<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Providers;

use Illuminate\Support\ServiceProvider;
use ItDevgroup\LaravelEmailTemplateLite\Console\Commands\EmailTemplatePublishCommand;
use ItDevgroup\LaravelEmailTemplateLite\Console\Commands\EmailTemplateSyncCommand;
use ItDevgroup\LaravelEmailTemplateLite\EmailTemplateService;
use ItDevgroup\LaravelEmailTemplateLite\EmailTemplateServiceInterface;

/**
 * Class EmailTemplateServiceProvider
 * @package ItDevgroup\LaravelEmailTemplateLite\Providers
 */
class EmailTemplateServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->loadCustomCommands();
        $this->loadCustomConfig();
        $this->loadCustomPublished();
        $this->loadCustomClasses();
    }

    /**
     * @return void
     */
    private function loadCustomCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    EmailTemplateSyncCommand::class,
                    EmailTemplatePublishCommand::class,
                ]
            );
        }
    }

    /**
     * @return void
     */
    private function loadCustomConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/email_template_lite.php', 'email_template_lite');
    }

    /**
     * @return void
     */
    private function loadCustomPublished()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../../config' => base_path('config')
                ],
                'config'
            );
            $this->publishes(
                [
                    __DIR__ . '/../../migration' => database_path('migrations')
                ],
                'migration'
            );
            $this->publishes(
                [
                    __DIR__ . '/../../resources' => resource_path()
                ],
                'resources'
            );
        }
    }

    /**
     * @return void
     */
    private function loadCustomClasses()
    {
        $this->app->singleton(EmailTemplateServiceInterface::class, EmailTemplateService::class);
    }
}
