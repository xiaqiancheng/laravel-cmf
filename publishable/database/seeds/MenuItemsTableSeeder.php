<?php

use Illuminate\Database\Seeder;
use XADMIN\LaravelCmf\Models\Menu;
use XADMIN\LaravelCmf\Models\MenuItem;

class MenuItemsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('name', 'admin')->firstOrFail();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('laravel-cmf::seeders.menu_items.dashboard'),
            'url'     => '',
            'route'   => 'laravel-cmf.dashboard',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'laravel-cmf-boat',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 1,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('laravel-cmf::seeders.menu_items.media'),
            'url'     => '',
            'route'   => 'laravel-cmf.media.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'laravel-cmf-images',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 5,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('laravel-cmf::seeders.menu_items.users'),
            'url'     => '',
            'route'   => 'laravel-cmf.users.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'laravel-cmf-person',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 3,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('laravel-cmf::seeders.menu_items.roles'),
            'url'     => '',
            'route'   => 'laravel-cmf.roles.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'laravel-cmf-lock',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 2,
            ])->save();
        }

        $toolsMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('laravel-cmf::seeders.menu_items.tools'),
            'url'     => '',
        ]);
        if (!$toolsMenuItem->exists) {
            $toolsMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'laravel-cmf-tools',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 9,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('laravel-cmf::seeders.menu_items.menu_builder'),
            'url'     => '',
            'route'   => 'laravel-cmf.menus.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'laravel-cmf-list',
                'color'      => null,
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 10,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('laravel-cmf::seeders.menu_items.database'),
            'url'     => '',
            'route'   => 'laravel-cmf.database.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'laravel-cmf-data',
                'color'      => null,
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 11,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('laravel-cmf::seeders.menu_items.compass'),
            'url'     => '',
            'route'   => 'laravel-cmf.compass.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'laravel-cmf-compass',
                'color'      => null,
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 12,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('laravel-cmf::seeders.menu_items.bread'),
            'url'     => '',
            'route'   => 'laravel-cmf.bread.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'laravel-cmf-bread',
                'color'      => null,
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 13,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('laravel-cmf::seeders.menu_items.settings'),
            'url'     => '',
            'route'   => 'laravel-cmf.settings.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'laravel-cmf-settings',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 14,
            ])->save();
        }
    }
}
