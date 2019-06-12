<?php

namespace XADMIN\LaravelCmf\Events;

use Illuminate\Queue\SerializesModels;
use XADMIN\LaravelCmf\Models\Menu;

class MenuDisplay
{
    use SerializesModels;

    public $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;

        // @deprecate
        //
        event('laravel-cmf.menu.display', $menu);
    }
}
