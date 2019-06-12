<?php

namespace XADMIN\LaravelCmf\Widgets;

use Illuminate\Support\Str;
use XADMIN\LaravelCmf\Facades\LaravelCmf;

class UserDimmer extends BaseDimmer
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = LaravelCmf::model('User')->count();
        $string = trans_choice('laravel-cmf::dimmer.user', $count);

        return view('laravel-cmf::dimmer', array_merge($this->config, [
            'icon'   => 'laravel-cmf-group',
            'title'  => "{$count} {$string}",
            'text'   => __('laravel-cmf::dimmer.user_text', ['count' => $count, 'string' => Str::lower($string)]),
            'button' => [
                'text' => __('laravel-cmf::dimmer.user_link_text'),
                'link' => route('laravel-cmf.users.index'),
            ],
            'image' => laravel_cmf_asset('images/widget-backgrounds/01.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return app('LaravelCmfAuth')->user()->can('browse', LaravelCmf::model('User'));
    }
}
