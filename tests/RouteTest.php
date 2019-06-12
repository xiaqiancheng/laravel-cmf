<?php

namespace XADMIN\LaravelCmf\Tests;

class RouteTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testGetRoutes()
    {
        $this->disableExceptionHandling();

        $this->visit(route('laravel-cmf.login'));
        $this->type('admin@admin.com', 'email');
        $this->type('password', 'password');
        $this->press(__('laravel-cmf::generic.login'));

        $urls = [
            route('laravel-cmf.dashboard'),
            route('laravel-cmf.media.index'),
            route('laravel-cmf.settings.index'),
            route('laravel-cmf.roles.index'),
            route('laravel-cmf.roles.create'),
            route('laravel-cmf.roles.show', ['role' => 1]),
            route('laravel-cmf.roles.edit', ['role' => 1]),
            route('laravel-cmf.users.index'),
            route('laravel-cmf.users.create'),
            route('laravel-cmf.users.show', ['user' => 1]),
            route('laravel-cmf.users.edit', ['user' => 1]),
            route('laravel-cmf.posts.index'),
            route('laravel-cmf.posts.create'),
            route('laravel-cmf.posts.show', ['post' => 1]),
            route('laravel-cmf.posts.edit', ['post' => 1]),
            route('laravel-cmf.pages.index'),
            route('laravel-cmf.pages.create'),
            route('laravel-cmf.pages.show', ['page' => 1]),
            route('laravel-cmf.pages.edit', ['page' => 1]),
            route('laravel-cmf.categories.index'),
            route('laravel-cmf.categories.create'),
            route('laravel-cmf.categories.show', ['category' => 1]),
            route('laravel-cmf.categories.edit', ['category' => 1]),
            route('laravel-cmf.menus.index'),
            route('laravel-cmf.menus.create'),
            route('laravel-cmf.menus.show', ['menu' => 1]),
            route('laravel-cmf.menus.edit', ['menu' => 1]),
            route('laravel-cmf.database.index'),
            route('laravel-cmf.bread.edit', ['table' => 'categories']),
            route('laravel-cmf.database.edit', ['table' => 'categories']),
            route('laravel-cmf.database.create'),
        ];

        foreach ($urls as $url) {
            $response = $this->call('GET', $url);
            $this->assertEquals(200, $response->status(), $url.' did not return a 200');
        }
    }
}
