<?php

namespace XADMIN\LaravelCmf\Providers;

use Arrilot\Widgets\ServiceProvider as WidgetServiceProvider;
use Illuminate\Support\ServiceProvider;

class LaravelCmfDummyServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(WidgetServiceProvider::class);

        $this->registerConfigs();

        if ($this->app->runningInConsole()) {
            $this->registerPublishableResources();
        }
    }

    /**
     * Register the publishable files.
     */
    private function registerPublishableResources()
    {
        $publishablePath = dirname(__DIR__).'/../publishable';

        $publishable = [
            'dummy_seeds' => [
                "{$publishablePath}/database/dummy_seeds/" => database_path('seeds'),
            ],
            'dummy_content' => [
                "{$publishablePath}/dummy_content/" => storage_path('app/public'),
            ],
            'dummy_config' => [
                "{$publishablePath}/config/laravel_cmf_dummy.php" => config_path('laravel_cmf.php'),
            ],
            'dummy_migrations' => [
                "{$publishablePath}/database/migrations/" => database_path('migrations'),
            ],

        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }

    public function registerConfigs()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/../publishable/config/laravel_cmf_dummy.php', 'laravel-cmf'
        );
    }
}
