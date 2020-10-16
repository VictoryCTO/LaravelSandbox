<?php

namespace joeyfrich\imageresizer;

use Illuminate\Support\ServiceProvider;

class imageresizerServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'joeyfrich');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'joeyfrich');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

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
        $this->mergeConfigFrom(__DIR__.'/../config/imageresizer.php', 'imageresizer');

        // Register the service the package provides.
        $this->app->singleton('imageresizer', function ($app) {
            return new imageresizer;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['imageresizer'];
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
            __DIR__.'/../config/imageresizer.php' => config_path('imageresizer.php'),
        ], 'imageresizer.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/joeyfrich'),
        ], 'imageresizer.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/joeyfrich'),
        ], 'imageresizer.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/joeyfrich'),
        ], 'imageresizer.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
