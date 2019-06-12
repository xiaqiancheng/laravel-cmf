<?php

namespace XADMIN\LaravelCmf;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Larapack\Hooks\Events\Setup;
use Larapack\Hooks\HooksServiceProvider;
use XADMIN\LaravelCmf\Models\Menu;
use XADMIN\LaravelCmf\Models\MenuItem;
use XADMIN\LaravelCmf\Models\Permission;

class LaravelCmfHooksServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $configPath = dirname(__DIR__).'/publishable/config/laravel-cmf-hooks.php';

        $this->mergeConfigFrom($configPath, 'laravel-cmf-hooks');

        // Register the HooksServiceProvider
        $this->app->register(HooksServiceProvider::class);

        if (!$this->enabled()) {
            return;
        }

        if ($this->app->runningInConsole()) {
            $this->publishes(
                [$configPath => config_path('laravel-cmf-hooks.php')],
                'laravel-cmf-hooks-config'
            );
        }

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/hooks', 'laravel-cmf-hooks');
    }

    /**
     * Bootstrap the application services.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function boot(Dispatcher $events)
    {
        if (!$this->enabled()) {
            return;
        }
        if (config('laravel-cmf-hooks.add-route', true)) {
            $events->listen('laravel-cmf.admin.routing', [$this, 'addHookRoute']);
        }

        if (config('laravel-cmf-hooks.add-hook-menu-item', true)) {
            $events->listen(Setup::class, [$this, 'addHookMenuItem']);
        }

        if (config('laravel-cmf-hooks.add-hook-permissions', true)) {
            $events->listen(Setup::class, [$this, 'addHookPermissions']);
        }

        if (config('laravel-cmf-hooks.publish-vendor-files', true)) {
            $events->listen(Setup::class, [$this, 'publishVendorFiles']);
        }
    }

    public function addHookRoute($router)
    {
        $namespacePrefix = '\\'.config('laravel-cmf.controllers.namespace').'\\';

        $router->get('hooks', ['uses' => $namespacePrefix.'HooksController@index', 'as' => 'hooks']);
        $router->get('hooks/{name}/enable', ['uses' => $namespacePrefix.'HooksController@enable', 'as' => 'hooks.enable']);
        $router->get('hooks/{name}/disable', ['uses' => $namespacePrefix.'HooksController@disable', 'as' => 'hooks.disable']);
        $router->get('hooks/{name}/update', ['uses' => $namespacePrefix.'HooksController@update', 'as' => 'hooks.update']);
        $router->post('hooks', ['uses' => $namespacePrefix.'HooksController@install', 'as' => 'hooks.install']);
        $router->delete('hooks/{name}', ['uses' => $namespacePrefix.'HooksController@uninstall', 'as' => 'hooks.uninstall']);
    }

    public function addHookMenuItem()
    {
        $menu = Menu::where('name', 'admin')->first();

        if (is_null($menu)) {
            return;
        }

        $parentId = null;

        $toolsMenuItem = MenuItem::where('menu_id', $menu->id)
            ->where('title', __('laravel-cmf::seeders.menu_items.tools'))
            ->first();

        if ($toolsMenuItem) {
            $parentId = $toolsMenuItem->id;
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Hooks',
            'url'     => '',
            'route'   => 'laravel-cmf.hooks',
        ]);

        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'laravel-cmf-hook',
                'color'      => null,
                'parent_id'  => $parentId,
                'order'      => 13,
            ])->save();
        }
    }

    public function addHookPermissions()
    {
        Permission::firstOrCreate([
            'key'        => 'browse_hooks',
            'table_name' => null,
        ]);
    }

    public function publishVendorFiles()
    {
        Artisan::call('vendor:publish', ['--provider' => static::class]);
    }

    public function enabled()
    {
        if (config('laravel-cmf-hooks.enabled', true)) {
            return config('hooks.enabled', true);
        }

        return config('laravel-cmf-hooks.enabled', true);
    }
}
