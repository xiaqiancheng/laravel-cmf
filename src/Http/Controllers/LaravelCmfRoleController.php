<?php

namespace XADMIN\LaravelCmf\Http\Controllers;

use Illuminate\Http\Request;
use XADMIN\LaravelCmf\Facades\LaravelCmf;

class LaravelCmfRoleController extends LaravelCmfBaseController
{
    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = LaravelCmf::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('edit', app($dataType->model_name));

        //Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
            $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

            $data->permissions()->sync($request->input('permissions', []));

            return redirect()
            ->route("laravel-cmf.{$dataType->slug}.index")
            ->with([
                'message'    => __('laravel-cmf::generic.successfully_updated')." {$dataType->display_name_singular}",
                'alert-type' => 'success',
                ]);
        }
    }

    // POST BRE(A)D
    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = LaravelCmf::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        //Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            $data = new $dataType->model_name();
            $this->insertUpdateData($request, $slug, $dataType->addRows, $data);

            $data->permissions()->sync($request->input('permissions', []));

            return redirect()
            ->route("laravel-cmf.{$dataType->slug}.index")
            ->with([
                'message'    => __('laravel-cmf::generic.successfully_added_new')." {$dataType->display_name_singular}",
                'alert-type' => 'success',
                ]);
        }
    }
}
