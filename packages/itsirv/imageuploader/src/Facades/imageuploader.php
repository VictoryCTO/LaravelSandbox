<?php

namespace itsirv\imageuploader\Facades;

use Illuminate\Support\Facades\Facade;

class imageuploader extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'imageuploader';
    }
}
