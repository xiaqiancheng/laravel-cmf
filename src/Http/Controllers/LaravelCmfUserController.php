<?php

namespace XADMIN\LaravelCmf\Http\Controllers;

use Illuminate\Http\Request;
use XADMIN\LaravelCmf\Facades\LaravelCmf;

class LaravelCmfUserController extends LaravelCmfBaseController
{
    public function profile(Request $request)
    {
        return LaravelCmf::view('laravel-cmf::profile');
    }

    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        if (app('LaravelCmfAuth')->user()->getKey() == $id) {
            $request->merge([
                'role_id'                              => app('LaravelCmfAuth')->user()->role_id,
                'user_belongsto_role_relationship'     => app('LaravelCmfAuth')->user()->role_id,
                'user_belongstomany_role_relationship' => app('LaravelCmfAuth')->user()->roles->pluck('id')->toArray(),
            ]);
        }

        return parent::update($request, $id);
    }
}
