<div class="side-menu sidebar-inverse">
    <nav class="navbar navbar-default" role="navigation">
        <div class="side-menu-container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('laravel-cmf.dashboard') }}">
                    <div class="logo-icon-container">
                        <?php $admin_logo_img = LaravelCmf::setting('admin.icon_image', ''); ?>
                        @if($admin_logo_img == '')
                            <img src="{{ laravel_cmf_asset('images/logo-icon-light.png') }}" alt="Logo Icon">
                        @else
                            <img src="{{ LaravelCmf::image($admin_logo_img) }}" alt="Logo Icon">
                        @endif
                    </div>
                    <div class="title">{{LaravelCmf::setting('admin.title', 'LARAVELCMF')}}</div>
                </a>
            </div><!-- .navbar-header -->

            <div class="panel widget center bgimage"
                 style="background-image:url({{ LaravelCmf::image( LaravelCmf::setting('admin.bg_image'), laravel_cmf_asset('images/bg.jpg') ) }}); background-size: cover; background-position: 0px;">
                <div class="dimmer"></div>
                <div class="panel-content">
                    <img src="{{ $user_avatar }}" class="avatar" alt="{{ app('LaravelCmfAuth')->user()->name }} avatar">
                    <h4>{{ ucwords(app('LaravelCmfAuth')->user()->name) }}</h4>
                    <p>{{ app('LaravelCmfAuth')->user()->email }}</p>

                    <a href="{{ route('laravel-cmf.profile') }}" class="btn btn-primary">{{ __('laravel-cmf::generic.profile') }}</a>
                    <div style="clear:both"></div>
                </div>
            </div>

        </div>
        <div id="adminmenu">
            <admin-menu :items="{{ menu('admin', '_json') }}"></admin-menu>
        </div>
    </nav>
</div>
