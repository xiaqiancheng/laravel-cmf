@extends('laravel-cmf::master')

@section('page_title', __('laravel-cmf::generic.viewing').' '.__('laravel-cmf::generic.database'))

@section('page_header')
    <h1 class="page-title">
        <i class="laravel-cmf-data"></i> {{ __('laravel-cmf::generic.database') }}
        <a href="{{ route('laravel-cmf.database.create') }}" class="btn btn-success"><i class="laravel-cmf-plus"></i>
            {{ __('laravel-cmf::database.create_new_table') }}</a>
    </h1>
@stop

@section('content')

    <div class="page-content container-fluid">
        @include('laravel-cmf::alerts')
        <div class="row">
            <div class="col-md-12">

                <table class="table table-striped database-tables">
                    <thead>
                        <tr>
                            <th>{{ __('laravel-cmf::database.table_name') }}</th>
                            <th style="text-align:right" colspan="2">{{ __('laravel-cmf::database.table_actions') }}</th>
                        </tr>
                    </thead>

                @foreach($tables as $table)
                    @continue(in_array($table->name, config('laravel-cmf.database.tables.hidden', [])))
                    <tr>
                        <td>
                            <p class="name">
                                <a href="{{ route('laravel-cmf.database.show', $table->name) }}"
                                   data-name="{{ $table->name }}" class="desctable">
                                   {{ $table->name }}
                                </a>
                            </p>
                        </td>

                        <td>
                            <div class="bread_actions">
                            @if($table->dataTypeId)
                                <a href="{{ route('laravel-cmf.' . $table->slug . '.index') }}"
                                   class="btn-sm btn-warning browse_bread">
                                    <i class="laravel-cmf-plus"></i> {{ __('laravel-cmf::database.browse_bread') }}
                                </a>
                                <a href="{{ route('laravel-cmf.bread.edit', $table->name) }}"
                                   class="btn-sm btn-default edit">
                                   {{ __('laravel-cmf::bread.edit_bread') }}
                                </a>
                                <a data-id="{{ $table->dataTypeId }}" data-name="{{ $table->name }}"
                                     class="btn-sm btn-danger delete">
                                     {{ __('laravel-cmf::bread.delete_bread') }}
                                </a>
                            @else
                                <a href="{{ route('laravel-cmf.bread.create', ['name' => $table->name]) }}"
                                   class="btn-sm btn-default">
                                    <i class="laravel-cmf-plus"></i> {{ __('laravel-cmf::bread.add_bread') }}
                                </a>
                            @endif
                            </div>
                        </td>

                        <td class="actions">
                            <a class="btn btn-danger btn-sm pull-right delete_table @if($table->dataTypeId) remove-bread-warning @endif"
                               data-table="{{ $table->name }}">
                               <i class="laravel-cmf-trash"></i> {{ __('laravel-cmf::generic.delete') }}
                            </a>
                            <a href="{{ route('laravel-cmf.database.edit', $table->name) }}"
                               class="btn btn-sm btn-primary pull-right" style="display:inline; margin-right:10px;">
                               <i class="laravel-cmf-edit"></i> {{ __('laravel-cmf::generic.edit') }}
                            </a>
                            <a href="{{ route('laravel-cmf.database.show', $table->name) }}"
                               data-name="{{ $table->name }}"
                               class="btn btn-sm btn-warning pull-right desctable" style="display:inline; margin-right:10px;">
                               <i class="laravel-cmf-eye"></i> {{ __('laravel-cmf::generic.view') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </table>
            </div>
        </div>
    </div>

    {{-- Delete BREAD Modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_bread_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('laravel-cmf::generic.close') }}"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="laravel-cmf-trash"></i>  {!! __('laravel-cmf::bread.delete_bread_quest', ['table' => '<span id="delete_bread_name"></span>']) !!}</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('laravel-cmf.bread.delete', ['id' => null]) }}" id="delete_bread_form" method="POST">
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ __('laravel-cmf::bread.delete_bread_conf') }}">
                    </form>
                    <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">{{ __('laravel-cmf::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('laravel-cmf::generic.close') }}"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="laravel-cmf-trash"></i> {!! __('laravel-cmf::database.delete_table_question', ['table' => '<span id="delete_table_name"></span>']) !!}</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_table_form" method="POST">
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger pull-right" value="{{ __('laravel-cmf::database.delete_table_confirm') }}">
                        <button type="button" class="btn btn-outline pull-right" style="margin-right:10px;"
                                data-dismiss="modal">{{ __('laravel-cmf::generic.cancel') }}
                        </button>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal modal-info fade" tabindex="-1" id="table_info" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('laravel-cmf::generic.close') }}"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="laravel-cmf-data"></i> @{{ table.name }}</h4>
                </div>
                <div class="modal-body" style="overflow:scroll">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('laravel-cmf::database.field') }}</th>
                            <th>{{ __('laravel-cmf::database.type') }}</th>
                            <th>{{ __('laravel-cmf::database.null') }}</th>
                            <th>{{ __('laravel-cmf::database.key') }}</th>
                            <th>{{ __('laravel-cmf::database.default') }}</th>
                            <th>{{ __('laravel-cmf::database.extra') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="row in table.rows">
                            <td><strong>@{{ row.Field }}</strong></td>
                            <td>@{{ row.Type }}</td>
                            <td>@{{ row.Null }}</td>
                            <td>@{{ row.Key }}</td>
                            <td>@{{ row.Default }}</td>
                            <td>@{{ row.Extra }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">{{ __('laravel-cmf::generic.close') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@stop

@section('javascript')

    <script>

        var table = {
            name: '',
            rows: []
        };

        new Vue({
            el: '#table_info',
            data: {
                table: table,
            },
        });

        $(function () {

            // Setup Show Table Info
            //
            $('.database-tables').on('click', '.desctable', function (e) {
                e.preventDefault();
                href = $(this).attr('href');
                table.name = $(this).data('name');
                table.rows = [];
                $.get(href, function (data) {
                    $.each(data, function (key, val) {
                        table.rows.push({
                            Field: val.field,
                            Type: val.type,
                            Null: val.null,
                            Key: val.key,
                            Default: val.default,
                            Extra: val.extra
                        });
                        $('#table_info').modal('show');
                    });
                });
            });

            // Setup Delete Table
            //
            $('td.actions').on('click', '.delete_table', function (e) {
                table = $(this).data('table');
                if ($(this).hasClass('remove-bread-warning')) {
                    toastr.warning('{{ __('laravel-cmf::database.delete_bread_before_table') }}');
                } else {
                    $('#delete_table_name').text(table);

                    $('#delete_table_form')[0].action = '{{ route('laravel-cmf.database.destroy', ['database' => '__database']) }}'.replace('__database', table)
                    $('#delete_modal').modal('show');
                }
            });

            // Setup Delete BREAD
            //
            $('table .bread_actions').on('click', '.delete', function (e) {
                id = $(this).data('id');
                name = $(this).data('name');

                $('#delete_bread_name').text(name);
                $('#delete_bread_form')[0].action += '/' + id;
                $('#delete_bread_modal').modal('show');
            });
        });
    </script>

@stop
