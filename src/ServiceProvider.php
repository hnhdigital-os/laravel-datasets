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
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\ListDatasetsCommand::class,
                Commands\MigrateCommand::class,
                Commands\SyncDataCommand::class,
            ]);
        }
    }
}
