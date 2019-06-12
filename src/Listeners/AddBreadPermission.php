<?php

namespace XADMIN\LaravelCmf\Listeners;

use XADMIN\LaravelCmf\Events\BreadAdded;
use XADMIN\LaravelCmf\Facades\LaravelCmf;

class AddBreadPermission
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
     * Create Permission for a given BREAD.
     *
     * @param BreadAdded $event
     *
     * @return void
     */
    public function handle(BreadAdded $bread)
    {
        if (config('laravel-cmf.bread.add_permission') && file_exists(base_path('routes/web.php'))) {
            // Create permission
            //
            // Permission::generateFor(snake_case($bread->dataType->slug));
            $role = LaravelCmf::model('Role')->where('name', config('laravel-cmf.bread.default_role'))->firstOrFail();

            // Get permission for added table
            $permissions = LaravelCmf::model('Permission')->where(['table_name' => $bread->dataType->name])->get()->pluck('id')->all();

            // Assign permission to admin
            $role->permissions()->attach($permissions);
        }
    }
}
