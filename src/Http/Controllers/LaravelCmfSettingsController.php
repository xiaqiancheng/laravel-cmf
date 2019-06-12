<?php

namespace XADMIN\LaravelCmf\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use XADMIN\LaravelCmf\Facades\LaravelCmf;

class LaravelCmfSettingsController extends Controller
{
    public function index()
    {
        // Check permission
        $this->authorize('browse', LaravelCmf::model('Setting'));

        $data = LaravelCmf::model('Setting')->orderBy('order', 'ASC')->get();

        $settings = [];
        $settings[__('laravel-cmf::settings.group_general')] = [];
        foreach ($data as $d) {
            if ($d->group == '' || $d->group == __('laravel-cmf::settings.group_general')) {
                $settings[__('laravel-cmf::settings.group_general')][] = $d;
            } else {
                $settings[$d->group][] = $d;
            }
        }
        if (count($settings[__('laravel-cmf::settings.group_general')]) == 0) {
            unset($settings[__('laravel-cmf::settings.group_general')]);
        }

        $groups_data = LaravelCmf::model('Setting')->select('group')->distinct()->get();
        $groups = [];
        foreach ($groups_data as $group) {
            if ($group->group != '') {
                $groups[] = $group->group;
            }
        }

        $active = (request()->session()->has('setting_tab')) ? request()->session()->get('setting_tab') : old('setting_tab', key($settings));

        return LaravelCmf::view('laravel-cmf::settings.index', compact('settings', 'groups', 'active'));
    }

    public function store(Request $request)
    {
        // Check permission
        $this->authorize('add', LaravelCmf::model('Setting'));

        $key = implode('.', [Str::slug($request->input('group')), $request->input('key')]);
        $key_check = LaravelCmf::model('Setting')->where('key', $key)->get()->count();

        if ($key_check > 0) {
            return back()->with([
                'message'    => __('laravel-cmf::settings.key_already_exists', ['key' => $key]),
                'alert-type' => 'error',
            ]);
        }

        $lastSetting = LaravelCmf::model('Setting')->orderBy('order', 'DESC')->first();

        if (is_null($lastSetting)) {
            $order = 0;
        } else {
            $order = intval($lastSetting->order) + 1;
        }

        $request->merge(['order' => $order]);
        $request->merge(['value' => '']);
        $request->merge(['key' => $key]);

        LaravelCmf::model('Setting')->create($request->except('setting_tab'));

        request()->flashOnly('setting_tab');

        return back()->with([
            'message'    => __('laravel-cmf::settings.successfully_created'),
            'alert-type' => 'success',
        ]);
    }

    public function update(Request $request)
    {
        // Check permission
        $this->authorize('edit', LaravelCmf::model('Setting'));

        $settings = LaravelCmf::model('Setting')->all();

        foreach ($settings as $setting) {
            $content = $this->getContentBasedOnType($request, 'settings', (object) [
                'type'    => $setting->type,
                'field'   => str_replace('.', '_', $setting->key),
                'details' => $setting->details,
                'group'   => $setting->group,
            ]);

            if ($setting->type == 'image' && $content == null) {
                continue;
            }

            if ($setting->type == 'file' && $content == json_encode([])) {
                continue;
            }

            $key = preg_replace('/^'.Str::slug($setting->group).'./i', '', $setting->key);

            $setting->group = $request->input(str_replace('.', '_', $setting->key).'_group');
            $setting->key = implode('.', [Str::slug($setting->group), $key]);
            $setting->value = $content;
            $setting->save();
        }

        request()->flashOnly('setting_tab');

        return back()->with([
            'message'    => __('laravel-cmf::settings.successfully_saved'),
            'alert-type' => 'success',
        ]);
    }

    public function delete($id)
    {
        // Check permission
        $this->authorize('delete', LaravelCmf::model('Setting'));

        $setting = LaravelCmf::model('Setting')->find($id);

        LaravelCmf::model('Setting')->destroy($id);

        request()->session()->flash('setting_tab', $setting->group);

        return back()->with([
            'message'    => __('laravel-cmf::settings.successfully_deleted'),
            'alert-type' => 'success',
        ]);
    }

    public function move_up($id)
    {
        // Check permission
        $this->authorize('edit', LaravelCmf::model('Setting'));

        $setting = LaravelCmf::model('Setting')->find($id);

        // Check permission
        $this->authorize('browse', $setting);

        $swapOrder = $setting->order;
        $previousSetting = LaravelCmf::model('Setting')
                            ->where('order', '<', $swapOrder)
                            ->where('group', $setting->group)
                            ->orderBy('order', 'DESC')->first();
        $data = [
            'message'    => __('laravel-cmf::settings.already_at_top'),
            'alert-type' => 'error',
        ];

        if (isset($previousSetting->order)) {
            $setting->order = $previousSetting->order;
            $setting->save();
            $previousSetting->order = $swapOrder;
            $previousSetting->save();

            $data = [
                'message'    => __('laravel-cmf::settings.moved_order_up', ['name' => $setting->display_name]),
                'alert-type' => 'success',
            ];
        }

        request()->session()->flash('setting_tab', $setting->group);

        return back()->with($data);
    }

    public function delete_value($id)
    {
        $setting = LaravelCmf::model('Setting')->find($id);

        // Check permission
        $this->authorize('delete', $setting);

        if (isset($setting->id)) {
            // If the type is an image... Then delete it
            if ($setting->type == 'image') {
                if (Storage::disk(config('laravel-cmf.storage.disk'))->exists($setting->value)) {
                    Storage::disk(config('laravel-cmf.storage.disk'))->delete($setting->value);
                }
            }
            $setting->value = '';
            $setting->save();
        }

        request()->session()->flash('setting_tab', $setting->group);

        return back()->with([
            'message'    => __('laravel-cmf::settings.successfully_removed', ['name' => $setting->display_name]),
            'alert-type' => 'success',
        ]);
    }

    public function move_down($id)
    {
        // Check permission
        $this->authorize('edit', LaravelCmf::model('Setting'));

        $setting = LaravelCmf::model('Setting')->find($id);

        // Check permission
        $this->authorize('browse', $setting);

        $swapOrder = $setting->order;

        $previousSetting = LaravelCmf::model('Setting')
                            ->where('order', '>', $swapOrder)
                            ->where('group', $setting->group)
                            ->orderBy('order', 'ASC')->first();
        $data = [
            'message'    => __('laravel-cmf::settings.already_at_bottom'),
            'alert-type' => 'error',
        ];

        if (isset($previousSetting->order)) {
            $setting->order = $previousSetting->order;
            $setting->save();
            $previousSetting->order = $swapOrder;
            $previousSetting->save();

            $data = [
                'message'    => __('laravel-cmf::settings.moved_order_down', ['name' => $setting->display_name]),
                'alert-type' => 'success',
            ];
        }

        request()->session()->flash('setting_tab', $setting->group);

        return back()->with($data);
    }
}
