<?php

namespace XADMIN\LaravelCmf\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use XADMIN\LaravelCmf\Models\Role;

class RolesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testRoles()
    {
        $this->visit(route('laravel-cmf.login'))
             ->type('admin@admin.com', 'email')
             ->type('password', 'password')
             ->press(__('laravel-cmf::generic.login'))
             ->seePageIs(route('laravel-cmf.dashboard'));

        // Adding a New Role
        $this->visit(route('laravel-cmf.roles.create'))
             ->type('superadmin', 'name')
             ->type('Super Admin', 'display_name')
             ->press(__('laravel-cmf::generic.submit'))
             ->seePageIs(route('laravel-cmf.roles.index'))
             ->seeInDatabase('roles', ['name' => 'superadmin']);

        // Editing a Role
        $this->visit(route('laravel-cmf.roles.edit', 2))
             ->type('regular_user', 'name')
             ->press(__('laravel-cmf::generic.submit'))
             ->seePageIs(route('laravel-cmf.roles.index'))
             ->seeInDatabase('roles', ['name' => 'regular_user']);

        // Editing a Role
        $this->visit(route('laravel-cmf.roles.edit', 2))
             ->type('user', 'name')
             ->press(__('laravel-cmf::generic.submit'))
             ->seePageIs(route('laravel-cmf.roles.index'))
             ->seeInDatabase('roles', ['name' => 'user']);

        // Get the current super admin role
        $superadmin_role = Role::where('name', '=', 'superadmin')->first();

        // Deleting a Role
        $response = $this->call('DELETE', route('laravel-cmf.roles.destroy', $superadmin_role->id), ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $this->notSeeInDatabase('roles', ['name' => 'superadmin']);
    }
}
