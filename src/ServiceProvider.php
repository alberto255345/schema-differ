<?php

namespace DiffFramework;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use DiffFramework\Console\Commands\GenerateDiffMigration;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->singleton('schema-differ', function ($app) {
            return new SchemaDiffer();
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateDiffMigration::class,
            ]);
        }
    }
}
