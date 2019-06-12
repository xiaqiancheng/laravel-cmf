<?php

use Illuminate\Database\Seeder;
use XADMIN\LaravelCmf\Models\DataType;

class DataTypesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $dataType = $this->dataType('slug', 'users');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'users',
                'display_name_singular' => __('laravel-cmf::seeders.data_types.user.singular'),
                'display_name_plural'   => __('laravel-cmf::seeders.data_types.user.plural'),
                'icon'                  => 'laravel-cmf-person',
                'model_name'            => 'XADMIN\\LaravelCmf\\Models\\User',
                'policy_name'           => 'XADMIN\\LaravelCmf\\Policies\\UserPolicy',
                'controller'            => 'XADMIN\\LaravelCmf\\Http\\Controllers\\LaravelCmfUserController',
                'generate_permissions'  => 1,
                'description'           => '',
            ])->save();
        }

        $dataType = $this->dataType('slug', 'menus');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'menus',
                'display_name_singular' => __('laravel-cmf::seeders.data_types.menu.singular'),
                'display_name_plural'   => __('laravel-cmf::seeders.data_types.menu.plural'),
                'icon'                  => 'laravel-cmf-list',
                'model_name'            => 'XADMIN\\LaravelCmf\\Models\\Menu',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
            ])->save();
        }

        $dataType = $this->dataType('slug', 'roles');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'roles',
                'display_name_singular' => __('laravel-cmf::seeders.data_types.role.singular'),
                'display_name_plural'   => __('laravel-cmf::seeders.data_types.role.plural'),
                'icon'                  => 'laravel-cmf-lock',
                'model_name'            => 'XADMIN\\LaravelCmf\\Models\\Role',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
            ])->save();
        }
    }

    /**
     * [dataType description].
     *
     * @param [type] $field [description]
     * @param [type] $for   [description]
     *
     * @return [type] [description]
     */
    protected function dataType($field, $for)
    {
        return DataType::firstOrNew([$field => $for]);
    }
}
