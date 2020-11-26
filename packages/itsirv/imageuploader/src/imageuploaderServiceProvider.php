<?php

namespace itsirv\imageuploader;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Contracts\Http\Kernel;

class imageuploaderServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'imageloader');

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
        $this->mergeConfigFrom(__DIR__.'/../config/imageuploader.php', 'imageuploader');

        // Register the service the package provides.
        $this->app->singleton('imageuploader', function ($app) {
            return new imageuploader();
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['imageuploader'];
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
            __DIR__.'/../config/imageuploader.php' => config_path('imageuploader.php'),
        ], 'imageuploader.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/itsirv'),
        ], 'imageuploader.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/itsirv'),
        ], 'imageuploader.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/itsirv'),
        ], 'imageuploader.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
