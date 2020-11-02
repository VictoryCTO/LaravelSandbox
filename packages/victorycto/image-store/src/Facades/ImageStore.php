<?php

namespace victorycto\ImageStore\Facades;

use Illuminate\Support\Facades\Facade;

class ImageStore extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'imagestore';
    }
}
