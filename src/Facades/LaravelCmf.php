<?php

namespace XADMIN\LaravelCmf\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelCmf extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-cmf';
    }
}
