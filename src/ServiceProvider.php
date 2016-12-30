<?php

namespace Bluora\LaravelDatasets;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Console commands provided by this package.
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\ListCommand::class,
                Commands\InstallCommand::class,
                Commands\MigrateCommand::class,
                Commands\SyncCommand::class,
            ]);
        }

        // Publish the default config.
        $this->publishes([
            __DIR__.'/../config/config.datasets.php' => config_path('datasets.php'),
        ]);
    }
}
