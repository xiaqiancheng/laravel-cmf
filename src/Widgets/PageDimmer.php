<?php

namespace XADMIN\LaravelCmf\Widgets;

use Illuminate\Support\Str;
use XADMIN\LaravelCmf\Facades\LaravelCmf;

class PageDimmer extends BaseDimmer
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
        $count = LaravelCmf::model('Page')->count();
        $string = trans_choice('laravel-cmf::dimmer.page', $count);

        return view('laravel-cmf::dimmer', array_merge($this->config, [
            'icon'   => 'laravel-cmf-file-text',
            'title'  => "{$count} {$string}",
            'text'   => __('laravel-cmf::dimmer.page_text', ['count' => $count, 'string' => Str::lower($string)]),
            'button' => [
                'text' => __('laravel-cmf::dimmer.page_link_text'),
                'link' => route('laravel-cmf.pages.index'),
            ],
            'image' => laravel_cmf_asset('images/widget-backgrounds/03.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return app('LaravelCmfAuth')->user()->can('browse', LaravelCmf::model('Page'));
    }
}
