@extends('layouts.admin')
@section('content')
    <div class="row mb-2">
        <div class="col-sm-12">
            <h2>
                {{ trans('cruds.source.title_singular') }} {{ trans('global.list') }}
            </h2>
        </div>
    </div>
    <div class="card card-primary card-outline">
        @if (auth()->user()->is_superadmin)
            <div class="card-header">
                <a class="btn btn-success float-right" href="{{ route('admin.sources.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.source.title_singular') }}
                </a>
        @endif
    </div>
    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Source">
            <thead>
                <tr>
                    <th width="10">
                    </th>
                    <th>
                        {{ trans('cruds.source.fields.project') }}
                    </th>
                    <th>
                        {{ trans('cruds.source.fields.campaign') }}
                    </th>
                    <th>
                        {{ trans('cruds.source.fields.name') }}
                    </th>
                    <th>
                        {{ trans('messages.source_name') }}
                        <i class="fas fa-info-circle" data-html="true" data-toggle="tooltip"
                            title="{{ trans('messages.source_name_help_text') }}">
                        </i>
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        @if (empty($__global_clients_filter))
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach ($projects as $key => $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>
                    <td>
                        @if (empty($__global_clients_filter))
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach ($campaigns as $key => $item)
                                    <option value="{{ $item->campaign_name }}">{{ $item->campaign_name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td></td>
                    <td>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
    </div>
@endsection
@section('scripts')
    @parent

    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('source_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.sources.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.sources.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'project_name',
                        name: 'project.name'
                    },
                    {
                        data: 'campaign_campaign_name',
                        name: 'campaign.campaign_name'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'source_name',
                        name: 'source_name'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [3, 'desc']
                ],
                pageLength: 100,
            };
            let table = $('.datatable-Source').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            let visibleColumnsIndexes = null;
            $('.datatable thead').on('input', '.search', function() {
                let strict = $(this).attr('strict') || false
                let value = strict && this.value ? "^" + this.value + "$" : this.value

                let index = $(this).parent().index()
                if (visibleColumnsIndexes !== null) {
                    index = visibleColumnsIndexes[index]
                }

                table
                    .column(index)
                    .search(value, strict)
                    .draw()
            });
            table.on('column-visibility.dt', function(e, settings, column, state) {
                visibleColumnsIndexes = []
                table.columns(":visible").every(function(colIdx) {
                    visibleColumnsIndexes.push(colIdx);
                });
            })
        });
    </script>
@endsection
