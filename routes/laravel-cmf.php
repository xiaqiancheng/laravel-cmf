<?php

use Illuminate\Support\Str;
use XADMIN\LaravelCmf\Events\Routing;
use XADMIN\LaravelCmf\Events\RoutingAdmin;
use XADMIN\LaravelCmf\Events\RoutingAdminAfter;
use XADMIN\LaravelCmf\Events\RoutingAfter;
use XADMIN\LaravelCmf\Facades\LaravelCmf;

/*
|--------------------------------------------------------------------------
| LaravelCmf Routes
|--------------------------------------------------------------------------
|
| This file is where you may override any of the routes that are included
| with LaravelCmf.
|
*/

Route::group(['as' => 'laravel-cmf.'], function () {
    event(new Routing());

    $namespacePrefix = '\\'.config('laravel-cmf.controllers.namespace').'\\';

    Route::get('login', ['uses' => $namespacePrefix.'LaravelCmfAuthController@login',     'as' => 'login']);
    Route::post('login', ['uses' => $namespacePrefix.'LaravelCmfAuthController@postLogin', 'as' => 'postlogin']);

    Route::group(['middleware' => 'admin.user'], function () use ($namespacePrefix) {
        event(new RoutingAdmin());

        // Main Admin and Logout Route
        Route::get('/', ['uses' => $namespacePrefix.'LaravelCmfController@index',   'as' => 'dashboard']);
        Route::post('logout', ['uses' => $namespacePrefix.'LaravelCmfController@logout',  'as' => 'logout']);
        Route::post('upload', ['uses' => $namespacePrefix.'LaravelCmfController@upload',  'as' => 'upload']);

        Route::get('profile', ['uses' => $namespacePrefix.'LaravelCmfUserController@profile', 'as' => 'profile']);

        try {
            foreach (LaravelCmf::model('DataType')::all() as $dataType) {
                $breadController = $dataType->controller
                                 ? Str::start($dataType->controller, '\\')
                                 : $namespacePrefix.'LaravelCmfBaseController';

                Route::get($dataType->slug.'/order', $breadController.'@order')->name($dataType->slug.'.order');
                Route::post($dataType->slug.'/action', $breadController.'@action')->name($dataType->slug.'.action');
                Route::post($dataType->slug.'/order', $breadController.'@update_order')->name($dataType->slug.'.order');
                Route::get($dataType->slug.'/{id}/restore', $breadController.'@restore')->name($dataType->slug.'.restore');
                Route::get($dataType->slug.'/relation', $breadController.'@relation')->name($dataType->slug.'.relation');
                Route::resource($dataType->slug, $breadController);
            }
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException("Custom routes hasn't been configured because: ".$e->getMessage(), 1);
        } catch (\Exception $e) {
            // do nothing, might just be because table not yet migrated.
        }

        // Role Routes
        Route::resource('roles', $namespacePrefix.'LaravelCmfRoleController');

        // Menu Routes
        Route::group([
            'as'     => 'menus.',
            'prefix' => 'menus/{menu}',
        ], function () use ($namespacePrefix) {
            Route::get('builder', ['uses' => $namespacePrefix.'LaravelCmfMenuController@builder',    'as' => 'builder']);
            Route::post('order', ['uses' => $namespacePrefix.'LaravelCmfMenuController@order_item', 'as' => 'order']);

            Route::group([
                'as'     => 'item.',
                'prefix' => 'item',
            ], function () use ($namespacePrefix) {
                Route::delete('{id}', ['uses' => $namespacePrefix.'LaravelCmfMenuController@delete_menu', 'as' => 'destroy']);
                Route::post('/', ['uses' => $namespacePrefix.'LaravelCmfMenuController@add_item',    'as' => 'add']);
                Route::put('/', ['uses' => $namespacePrefix.'LaravelCmfMenuController@update_item', 'as' => 'update']);
            });
        });

        // Settings
        Route::group([
            'as'     => 'settings.',
            'prefix' => 'settings',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'LaravelCmfSettingsController@index',        'as' => 'index']);
            Route::post('/', ['uses' => $namespacePrefix.'LaravelCmfSettingsController@store',        'as' => 'store']);
            Route::put('/', ['uses' => $namespacePrefix.'LaravelCmfSettingsController@update',       'as' => 'update']);
            Route::delete('{id}', ['uses' => $namespacePrefix.'LaravelCmfSettingsController@delete',       'as' => 'delete']);
            Route::get('{id}/move_up', ['uses' => $namespacePrefix.'LaravelCmfSettingsController@move_up',      'as' => 'move_up']);
            Route::get('{id}/move_down', ['uses' => $namespacePrefix.'LaravelCmfSettingsController@move_down',    'as' => 'move_down']);
            Route::put('{id}/delete_value', ['uses' => $namespacePrefix.'LaravelCmfSettingsController@delete_value', 'as' => 'delete_value']);
        });

        // Admin Media
        Route::group([
            'as'     => 'media.',
            'prefix' => 'media',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'LaravelCmfMediaController@index',              'as' => 'index']);
            Route::post('files', ['uses' => $namespacePrefix.'LaravelCmfMediaController@files',              'as' => 'files']);
            Route::post('new_folder', ['uses' => $namespacePrefix.'LaravelCmfMediaController@new_folder',         'as' => 'new_folder']);
            Route::post('delete_file_folder', ['uses' => $namespacePrefix.'LaravelCmfMediaController@delete', 'as' => 'delete']);
            Route::post('move_file', ['uses' => $namespacePrefix.'LaravelCmfMediaController@move',          'as' => 'move']);
            Route::post('rename_file', ['uses' => $namespacePrefix.'LaravelCmfMediaController@rename',        'as' => 'rename']);
            Route::post('upload', ['uses' => $namespacePrefix.'LaravelCmfMediaController@upload',             'as' => 'upload']);
            Route::post('remove', ['uses' => $namespacePrefix.'LaravelCmfMediaController@remove',             'as' => 'remove']);
            Route::post('crop', ['uses' => $namespacePrefix.'LaravelCmfMediaController@crop',             'as' => 'crop']);
        });

        // BREAD Routes
        Route::group([
            'as'     => 'bread.',
            'prefix' => 'bread',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'LaravelCmfBreadController@index',              'as' => 'index']);
            Route::get('{table}/create', ['uses' => $namespacePrefix.'LaravelCmfBreadController@create',     'as' => 'create']);
            Route::post('/', ['uses' => $namespacePrefix.'LaravelCmfBreadController@store',   'as' => 'store']);
            Route::get('{table}/edit', ['uses' => $namespacePrefix.'LaravelCmfBreadController@edit', 'as' => 'edit']);
            Route::put('{id}', ['uses' => $namespacePrefix.'LaravelCmfBreadController@update',  'as' => 'update']);
            Route::delete('{id}', ['uses' => $namespacePrefix.'LaravelCmfBreadController@destroy',  'as' => 'delete']);
            Route::post('relationship', ['uses' => $namespacePrefix.'LaravelCmfBreadController@addRelationship',  'as' => 'relationship']);
            Route::get('delete_relationship/{id}', ['uses' => $namespacePrefix.'LaravelCmfBreadController@deleteRelationship',  'as' => 'delete_relationship']);
        });

        // Database Routes
        Route::resource('database', $namespacePrefix.'LaravelCmfDatabaseController');

        // Compass Routes
        Route::group([
            'as'     => 'compass.',
            'prefix' => 'compass',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'LaravelCmfCompassController@index',  'as' => 'index']);
            Route::post('/', ['uses' => $namespacePrefix.'LaravelCmfCompassController@index',  'as' => 'post']);
        });

        event(new RoutingAdminAfter());
    });

    //Asset Routes
    Route::get('assets', ['uses' => $namespacePrefix.'LaravelCmfController@assets', 'as' => 'assets']);

    event(new RoutingAfter());
});
