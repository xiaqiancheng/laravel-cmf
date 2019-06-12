@extends('laravel-cmf::master')

@section('css')
    <style>
        .user-email {
            font-size: .85rem;
            margin-bottom: 1.5em;
        }
    </style>
@stop

@section('content')
    <div style="background-size:cover; background-image: url({{ LaravelCmf::image( LaravelCmf::setting('admin.bg_image'), laravel_cmf_asset('/images/bg.jpg')) }}); background-position: center center;position:absolute; top:0; left:0; width:100%; height:300px;"></div>
    <div style="height:160px; display:block; width:100%"></div>
    <div style="position:relative; z-index:9; text-align:center;">
        <img src="@if( !filter_var(app('LaravelCmfAuth')->user()->avatar, FILTER_VALIDATE_URL)){{ LaravelCmf::image( app('LaravelCmfAuth')->user()->avatar ) }}@else{{ app('LaravelCmfAuth')->user()->avatar }}@endif"
             class="avatar"
             style="border-radius:50%; width:150px; height:150px; border:5px solid #fff;"
             alt="{{ app('LaravelCmfAuth')->user()->name }} avatar">
        <h4>{{ ucwords(app('LaravelCmfAuth')->user()->name) }}</h4>
        <div class="user-email text-muted">{{ ucwords(app('LaravelCmfAuth')->user()->email) }}</div>
        <p>{{ app('LaravelCmfAuth')->user()->bio }}</p>
        <a href="{{ route('laravel-cmf.users.edit', app('LaravelCmfAuth')->user()->getKey()) }}" class="btn btn-primary">{{ __('laravel-cmf::profile.edit') }}</a>
    </div>
@stop
