<?php

namespace joeyfrich\imageresizer\Facades;

use Illuminate\Support\Facades\Facade;

class imageresizer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'imageresizer';
    }
}
