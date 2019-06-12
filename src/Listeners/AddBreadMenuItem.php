<?php

namespace XADMIN\LaravelCmf\Listeners;

use XADMIN\LaravelCmf\Events\BreadAdded;
use XADMIN\LaravelCmf\Facades\LaravelCmf;

class AddBreadMenuItem
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
     * Create a MenuItem for a given BREAD.
     *
     * @param BreadAdded $event
     *
     * @return void
     */
    public function handle(BreadAdded $bread)
    {
        if (config('laravel-cmf.bread.add_menu_item') && file_exists(base_path('routes/web.php'))) {
            require base_path('routes/web.php');

            $menu = LaravelCmf::model('Menu')->where('name', config('laravel-cmf.bread.default_menu'))->firstOrFail();

            $menuItem = LaravelCmf::model('MenuItem')->firstOrNew([
                'menu_id' => $menu->id,
                'title'   => $bread->dataType->display_name_plural,
                'url'     => '',
                'route'   => 'laravel-cmf.'.$bread->dataType->slug.'.index',
            ]);

            $order = LaravelCmf::model('MenuItem')->highestOrderMenuItem();

            if (!$menuItem->exists) {
                $menuItem->fill([
                    'target'     => '_self',
                    'icon_class' => $bread->dataType->icon,
                    'color'      => null,
                    'parent_id'  => null,
                    'order'      => $order,
                ])->save();
            }
        }
    }
}
