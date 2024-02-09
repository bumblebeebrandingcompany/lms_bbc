@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h2>
                @lang('messages.documents')
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary card-outline">

                @if(auth()->user()->is_superadmin)
                    <div class="card-header">
                        <a class="btn btn-success float-right" href="{{ route('admin.documents.create') }}">
                            {{ trans('global.add') }} {{ trans('messages.document') }}
                        </a>
                    </div>
                @endif
               <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-3">
                            <label for="project_id">
                                @lang('messages.projects')
                            </label>
                            <select class="search form-control" id="project_id">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($projects as $id => $title)
                                    <option value="{{ $id }}">{{ $title }}</option>
                                @endforeach
                            </
                        </div>
                    </div>
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-documents">
                        <thead>
                            <tr>
                                <th width="10">
                                </th>
                                <th>
                                    @lang('messages.title')
                                </th>
                                <th>
                                    @lang('messages.project')
                                </th>
                                <th>
                                    {{ trans('messages.added_by') }}
                                </th>
                                <th>
                                    {{ trans('messages.created_at') }}
                                </th>
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @if(auth()->user()->is_superadmin)
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.documents.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
                    return entry.id
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}')
                    return
                }

                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({
                    headers: {'x-csrf-token': _token},
                    method: 'POST',
                    url: config.url,
                    data: { ids: ids, _method: 'DELETE' }})
                    .done(function () { location.reload() })
                }
                }
            }
            dtButtons.push(deleteButton)
        @endif

        let dtOverrideGlobals = {
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route('admin.documents.index') }}",
                data: function (d) {
                    d.project_id = $("#project_id").val();
                }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'title', name: 'title' },
                { data: 'project_name', name: 'projects.name' },
                { data: 'added_by', name: 'users.name' },
                { data: 'created_at', name: 'documents.created_at' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 4, 'desc' ]],
            pageLength: 50,
        };

        let table = $('.datatable-documents').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
        });

        let visibleColumnsIndexes = null;

        $(document).on('change', '#project_id', function () {
            table.ajax.reload();
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
