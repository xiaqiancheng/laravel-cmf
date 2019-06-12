<?php

namespace XADMIN\LaravelCmf\Listeners;

use Cache;
use XADMIN\LaravelCmf\Events\SettingUpdated;

class ClearCachedSettingValue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * handle.
     *
     * @param SettingUpdated $event
     *
     * @return void
     */
    public function handle(SettingUpdated $event)
    {
        if (config('laravel-cmf.settings.cache', false) === true) {
            Cache::tags('settings')->forget($event->setting->key);
        }
    }
}
