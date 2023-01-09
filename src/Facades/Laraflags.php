<?php

namespace Faridibin\Laraflags\Facades;

use Illuminate\Support\Facades\Facade;

class Laraflags extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laraflags';
    }
}
