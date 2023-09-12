@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
            {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
        </h2>
   </div>
</div>
<div class="card card-primary card-outline">
    @if(auth()->user()->is_superadmin || auth()->user()->is_channel_partner_manager)
        <div class="card-header">
            <a class="btn btn-success float-right" href="{{ route('admin.users.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
            </a>
        </div>
    @endif
    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-User">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        @lang('messages.ref_num')
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.name') }}
                    </th>
                    <th>
                        {{ trans('messages.representative_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.email') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.user_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.contact_number_1') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.website') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.client') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.agency') }}
                    </th>
                    <th>
                        @lang('messages.assigned_projects')
                    </th>
                    <th>@lang('messages.added_on')</th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td></td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td></td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        @if(!auth()->user()->is_channel_partner_manager)
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach(App\Models\User::USER_TYPE_RADIO as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        @if(!auth()->user()->is_channel_partner_manager)
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($clients as $key => $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>
                    <td>
                        @if(!auth()->user()->is_channel_partner_manager)
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($agencies as $key => $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="modal fade" id="edit_pwd_modal" tabindex="-1" aria-hidden="true"></div>
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
                url: "{{ route('admin.users.massDestroy') }}",
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
            ajax: "{{ route('admin.users.index') }}",
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'ref_num', name: 'ref_num' },
                { data: 'name', name: 'name' },
                { data: 'representative_name', name: 'representative_name' },
                { data: 'email', name: 'email' },
                { data: 'user_type', name: 'user_type' },
                { data: 'contact_number_1', name: 'contact_number_1' },
                { data: 'website', name: 'website' },
                { data: 'client_name', name: 'client.name' },
                { data: 'agency_name', name: 'agency.name' },
                { data: 'assigned_projects', name: 'assigned_projects' },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 10, 'desc' ]],
            pageLength: 25,
        };

        let table = $('.datatable-User').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
        
        let visibleColumnsIndexes = null;
        $('.datatable thead').on('input', '.search', function () {
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

        $(document).on('click', '.edit_password', function() {
            $.ajax({
                method:"GET",
                url: $(this).attr('data-href'),
                dataType: "html",
                success: function(response) {
                    $("#edit_pwd_modal").html(response).modal(('show'));
                }
            });
        });

        $(document).on('click', '.update-password', function(e) {
            e.preventDefault();
            if($("#edit_pwd_form").valid()) {
                $.ajax({
                    method:"PUT",
                    url: $("#edit_pwd_form").attr('action'),
                    data: $("#edit_pwd_form").serialize() ,
                    dataType: "JSON",
                    success: function(response) {
                        if(response.success) {
                            $("#edit_pwd_modal").modal(('hide'));
                        }
                        setTimeout(() => {
                            alert(response.msg);
                        }, 1000);
                    }
                })
            };
        });
    });
</script>
@endsection