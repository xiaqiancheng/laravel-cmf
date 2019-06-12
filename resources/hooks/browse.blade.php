@extends('laravel-cmf::master')

@section('page_header')
    <h1 class="page-title">
        <i class="laravel-cmf-hook"></i> Hooks
        <div class="btn btn-success install">
            <i class="laravel-cmf-plus"></i> {{ __('laravel-cmf::generic.add_new') }}
        </div>
    </h1>
@stop

@section('page_header_actions')

@stop

@section('content')
    <div class="page-content container-fluid">
        @if (request()->has('message'))
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-{{ request('message_type', 'info') }}">
                        <p>{{ request('message') }}</p>
                    </div>
                </div>
            </div>
        @endif
        @if($daysSinceLastCheck >= 10)
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <p>You have not checked for any updates for the last {{ $daysSinceLastCheck }} days.</p>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Enabled</th>
                                <th class="actions">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($hooks as $hook)
                                <tr>
                                    <td>
                                        <i class="laravel-cmf-{{ $hook->type }}"></i> {{ $hook->name }}
                                    </td>
                                    <td>
                                        @if($hook->enabled)
                                                '<i class="laravel-cmf-check"></i> ' {{ __('laravel-cmf::generic.enable') }}
                                        @else
                                                '<i class="laravel-cmf-x"></i> ' {{ __('laravel-cmf::generic.disenable') }}
                                        @endif
                                    </td>
                                    <td class="no-sort no-click">
                                        <div class="btn-sm btn-danger pull-right delete" data-id="{{ $hook->name }}" id="delete-{{ $hook->name }}">
                                            <i class="laravel-cmf-trash"></i> {{ __('laravel-cmf::generic.uninstall') }}
                                        </div>
                                        <a href="{{ route('laravel-cmf.hooks.'.($hook->enabled ? 'disable' : 'enable'), $hook->name) }}" class="btn-sm btn-primary pull-right edit">
                                            <i class="laravel-cmf-edit"></i> {{ $hook->enabled ? __('laravel-cmf::generic.disenable') : __('laravel-cmf::generic.enable') }}
                                        </a>
                                        <?php /*
                                        @if ($hook->hasUpdateAvailable())
                                            <a href="{{ route('laravel-cmf.hooks.update', $hook->name) }}" class="btn-sm btn-warning pull-right update">
                                                <i class="laravel-cmf-edit"></i> Update
                                            </a>
                                        @endif
                                        */ ?>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="laravel-cmf-trash"></i> {{ __('laravel-cmf::generic.delete_question') }}</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('laravel-cmf.hooks') }}" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                               value="{{ __('laravel-cmf::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('laravel-cmf::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal modal-success fade" tabindex="-1" id="install_modal" role="dialog">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('laravel-cmf.hooks') }}" id="install_form" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="laravel-cmf-plus"></i>Install new hook.</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Name of hook">
                    </div>
                </div>
                <div class="modal-footer">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-success pull-right install-confirm"
                               value="{{ __('laravel-cmf::generic.install') }}">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('laravel-cmf::generic.cancel') }}</button>
                </div>
            </form><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('javascript')
    <!-- DataTables -->
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({ "order": [] });
        });

        $('.page-title').on('click', '.install', function (e) {
            var form = $('#install_form')[0];

            $('#install_modal').modal('show');
        });

        $('td').on('click', '.delete', function (e) {
            var form = $('#delete_form')[0];

            form.action = parseActionUrl(form.action, $(this).data('id'));

            $('#delete_modal').modal('show');
        });

        function parseActionUrl(action, id) {
            return action.match(/\/[0-9]+$/)
                    ? action.replace(/([0-9]+$)/, id)
                    : action + '/' + id;
        }
    </script>
@stop
