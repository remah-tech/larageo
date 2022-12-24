<?php

namespace Technoyer\Larageo\Providers;

use Illuminate\Support\Str;
use Technoyer\Larageo\Larageo;
use Illuminate\Support\ServiceProvider;

class LarageoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if( !$this->app->runningInConsole() )
        {
            $this->registerConfig();
        }

        $this->app->singleton('larageo', function ($app) {
            return new Larageo($app->config->get('larageo', []));
        });

        $this->app->bind(Larageo::class, 'larageo');
    }

    /**
     * Register Larageo config
     * @return void
     */
    public function registerConfig(): void
    {
        $app_version = Str::lower($this->app->version());
        $config_path = $this->app->make('path.config');

        //check if app running under Lumen
        if( false===Str::contains($app_version, 'lumen') )
        {
            $this->publishes([
                __DIR__ . '/../config/larageo.php', config_path('larageo.php')
            ], 'config');
        }
    }
    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
