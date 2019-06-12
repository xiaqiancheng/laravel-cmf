@if(config('laravel-cmf.show_dev_tips'))
    <div class="container-fluid">
        <div class="alert alert-info">
            <strong>{{ __('laravel-cmf::generic.how_to_use') }}:</strong>
            <p>{{ trans_choice('laravel-cmf::menu_builder.usage_hint', !empty($menu) ? 0 : 1) }} <code>menu('{{ !empty($menu) ? $menu->name : 'name' }}')</code></p>
        </div>
    </div>
@endif
