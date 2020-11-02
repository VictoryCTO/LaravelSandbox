<?php

namespace victorycto\ImageStore;

use Illuminate\Support\ServiceProvider;
use victorycto\ImageStore\Console\InstallImageStore;

class ImageStoreServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/imagestore.php', 'imagestore');

        // Register the service the package provides.
        $this->app->singleton('imagestore', function ($app) {
            return new ImageStore;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['imagestore'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/imagestore.php' => config_path('imagestore.php'),
        ]);

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/victorycto'),
        ], 'imagestore.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/victorycto'),
        ], 'imagestore.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/victorycto'),
        ], 'imagestore.views');*/

        // Registering package commands.
        $this->commands([
            InstallImageStore::class,
        ]);
    }
}
