<?php

namespace App\Providers;

use App\Services\ImageService;
use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind('ImageService', function () {
            return new ImageService();
        });
    }
}
