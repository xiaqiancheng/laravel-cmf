<footer class="app-footer">
    <div class="site-footer-right">
        @if (rand(1,100) == 100)
            <i class="laravel-cmf-rum-1"></i> {{ __('laravel-cmf::theme.footer_copyright2') }}
        @else
            {!! __('laravel-cmf::theme.footer_copyright') !!} <a href="https://github.com/xiaqiancheng/laravel-cmf" target="_blank">Laravel Cmf</a>
        @endif
        @php $version = LaravelCmf::getVersion(); @endphp
        @if (!empty($version))
            - {{ $version }}
        @endif
    </div>
</footer>
