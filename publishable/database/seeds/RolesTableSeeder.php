<?php

use Illuminate\Database\Seeder;
use XADMIN\LaravelCmf\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $role = Role::firstOrNew(['name' => 'admin']);
        if (!$role->exists) {
            $role->fill([
                    'display_name' => __('laravel-cmf::seeders.roles.admin'),
                ])->save();
        }

        $role = Role::firstOrNew(['name' => 'user']);
        if (!$role->exists) {
            $role->fill([
                    'display_name' => __('laravel-cmf::seeders.roles.user'),
                ])->save();
        }
    }
}
