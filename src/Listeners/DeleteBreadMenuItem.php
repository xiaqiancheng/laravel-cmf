<?php

namespace XADMIN\LaravelCmf\Listeners;

use XADMIN\LaravelCmf\Events\BreadDeleted;
use XADMIN\LaravelCmf\Facades\LaravelCmf;

class DeleteBreadMenuItem
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
     * Delete a MenuItem for a given BREAD.
     *
     * @param BreadDeleted $bread
     *
     * @return void
     */
    public function handle(BreadDeleted $bread)
    {
        if (config('laravel-cmf.bread.add_menu_item')) {
            $menuItem = LaravelCmf::model('MenuItem')->where('route', 'laravel-cmf.'.$bread->dataType->slug.'.index');

            if ($menuItem->exists()) {
                $menuItem->delete();
            }
        }
    }
}
