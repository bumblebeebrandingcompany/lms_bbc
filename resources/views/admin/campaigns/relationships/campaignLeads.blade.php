<div class="m-3">
    <div class="card">
        @if(auth()->user()->is_superadmin)
            <div class="card-header">
                <a class="btn btn-success float-right" href="{{ route('admin.leads.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.lead.title_singular') }}
                </a>
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-campaignLeads">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.lead.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.lead.fields.project') }}
                            </th>
                            <th>
                                {{ trans('cruds.lead.fields.campaign') }}
                            </th>
                            <th>
                                {{ trans('cruds.lead.fields.lead_details') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leads as $key => $lead)
                            <tr data-entry-id="{{ $lead->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $lead->id ?? '' }}
                                </td>
                                <td>
                                    {{ $lead->project->name ?? '' }}
                                </td>
                                <td>
                                    {{ $lead->campaign->campaign_name ?? '' }}
                                </td>
                                <td>
                                    @if(!empty($lead->lead_info) && is_array($lead->lead_info))
                                        @foreach($lead->lead_info as $key => $value)
                                            {{$key}} : {{$value}} <br>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.leads.show', $lead->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.leads.edit', $lead->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                    @if(auth()->user()->is_superadmin)
                                        <form action="{{ route('admin.leads.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@if(auth()->user()->is_superadmin)
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.leads.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
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

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-campaignLeads:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection