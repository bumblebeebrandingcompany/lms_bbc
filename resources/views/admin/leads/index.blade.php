@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
            {{ trans('cruds.lead.title_singular') }} {{ trans('global.list') }}
        </h2>
   </div>
</div>
<div class="card card-primary card-outline">
    @if(auth()->user()->is_superadmin)
        <div class="card-header">
            <a class="btn btn-success float-right" href="{{ route('admin.leads.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.lead.title_singular') }}
            </a>
        </div>
    @endif
    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Lead">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        @lang('messages.email')
                    </th>
                    <th>
                        @lang('messages.phone')
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.project') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.campaign') }}
                    </th>
                    <th>
                        {{ trans('messages.source') }}
                    </th>
                    <th>
                        {{ trans('messages.added_by') }}
                    </th>
                    <th>
                        {{ trans('messages.created_at') }}
                    </th>
                    <th>
                        {{ trans('messages.updated_at') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                        @if(!auth()->user()->is_agency)
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($projects as $key => $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($campaigns as $key => $item)
                                <option value="{{ $item->campaign_name }}">{{ $item->campaign_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td></td>
                    <td>
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
    url: "{{ route('admin.leads.massDestroy') }}",
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
    ajax: "{{ route('admin.leads.index') }}",
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        { data: 'email', name: 'email' },
        { data: 'phone', name: 'phone' },
        { data: 'project_name', name: 'project.name' },
        { data: 'campaign_campaign_name', name: 'campaign.campaign_name' },
        { data: 'source_name', name: 'source.name' },
        { data: 'added_by', name: 'added_by' },
        { data: 'created_at', name: 'leads.created_at' },
        { data: 'updated_at', name: 'leads.updated_at' },
        { data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 7, 'desc' ]],
    pageLength: 50,
  };
  let table = $('.datatable-Lead').DataTable(dtOverrideGlobals);
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
});

</script>
@endsection