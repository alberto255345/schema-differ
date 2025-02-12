<?php

namespace DiffFramework;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        // Registre o seu serviço no container do Laravel, se necessário.
        $this->app->singleton('schema-differ', function ($app) {
            return new SchemaDiffer();
        });
    }

    public function boot()
    {
        // Se você quiser publicar arquivos de configuração ou registrar comandos Artisan, faça isso aqui.
    }
}
