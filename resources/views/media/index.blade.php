@extends('laravel-cmf::master')

@section('page_title', __('laravel-cmf::generic.media'))

@section('content')
    <div class="page-content container-fluid">
        @include('laravel-cmf::alerts')
        <div class="row">
            <div class="col-md-12">

                <div class="admin-section-title">
                    <h3><i class="laravel-cmf-images"></i> {{ __('laravel-cmf::generic.media') }}</h3>
                </div>
                <div class="clear"></div>
                <div id="filemanager">
                    <media-manager
                        base-path="{{ config('laravel-cmf.media.path', '/') }}"
                        :show-folders="{{ config('laravel-cmf.media.show_folders', true) ? 'true' : 'false' }}"
                        :allow-upload="{{ config('laravel-cmf.media.allow_upload', true) ? 'true' : 'false' }}"
                        :allow-move="{{ config('laravel-cmf.media.allow_move', true) ? 'true' : 'false' }}"
                        :allow-delete="{{ config('laravel-cmf.media.allow_delete', true) ? 'true' : 'false' }}"
                        :allow-create-folder="{{ config('laravel-cmf.media.allow_create_folder', true) ? 'true' : 'false' }}"
                        :allow-rename="{{ config('laravel-cmf.media.allow_rename', true) ? 'true' : 'false' }}"
                        :allow-crop="{{ config('laravel-cmf.media.allow_crop', true) ? 'true' : 'false' }}"
                        ></media-manager>
                </div>
            </div><!-- .row -->
        </div><!-- .col-md-12 -->
    </div><!-- .page-content container-fluid -->
@stop

@section('javascript')
<script>
new Vue({
    el: '#filemanager'
});
</script>
@endsection
