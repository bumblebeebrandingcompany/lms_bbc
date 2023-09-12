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
    @if(auth()->user()->is_superadmin || auth()->user()->is_channel_partner)
        <div class="card-header">
            <a class="btn btn-success float-right" href="{{ route('admin.leads.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.lead.title_singular') }}
            </a>
        </div>
    @endif
    <div class="card-body">
        <div class="row mb-5">
            @if(!(auth()->user()->is_agency || auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager))
                <div class="col-md-3">
                    <label for="project_id">
                        @lang('messages.projects')
                    </label>
                    <select class="search form-control" id="project_id">
                        <option value>{{ trans('global.all') }}</option>
                        @foreach($projects as $key => $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if(!(auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager))
                <div class="col-md-3 campaigns_div">
                    <label for="campaign_id">
                        @lang('messages.campaigns')
                    </label>
                    <select class="search form-control" id="campaign_id">
                        <option value>{{ trans('global.all') }}</option>
                        @foreach($campaigns as $key => $item)
                            <option value="{{ $item->id }}">{{ $item->campaign_name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if(!(auth()->user()->is_agency || auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager))
                <div class="col-md-3 sources_div">
                    <label for="source_id">
                        Source
                    </label>
                    <select class="search form-control" name="source" id="source_id">
                        <option value>{{ trans('global.all') }}</option>
                        @foreach($sources as $source)
                            <option value="{{$source->id}}">{{ $source->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="added_on">{{ trans('messages.added_on') }}</label>
                    <input class="form-control date_range" type="text" name="date" id="added_on" readonly>
                </div>
            @endif
            <div class="col-md-3">
                <label for="leads_status">
                    @lang('messages.status')
                </label>
                <select class="search form-control" id="leads_status">
                    <option value>{{ trans('global.all') }}</option>
                    <option value="new">New</option>
                    <option value="duplicate">Duplicate</option>
                </select>
            </div>
            @if(auth()->user()->is_superadmin)
                <div class="col-md-3 mt-auto mb-2">
                    <div class="form-check">
                        <input class="form-check-input search" type="checkbox" id="no_lead_id" value="1">
                        <label for="no_lead_id" class="form-check-label">
                            @lang('messages.no_lead_id')
                        </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <label></label>
                    <button type="button" class="btn btn-block btn-outline-primary" id="send_bulk_outgoing_webhook">
                        @lang('messages.send_outgoing_webhook')
                    </button>
                </div>
            @endif
            <div class="col-md-12"><hr></div>
            @if(auth()->user()->is_superadmin)
                <div class="col-md-3 additional_columns_to_export_div"
                    style="display: none;">
                </div>
                <div class="col-md-3 mb-auto mt-auto">
                    <label></label>
                    <button type="button" class="btn btn-block btn-outline-info" id="download_excel">
                        @lang('messages.download_excel')
                    </button>
                </div>
            @endif
        </div>
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Lead">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        @lang('messages.ref_num')
                    </th>
                    <th>
                        @lang('messages.name')
                    </th>
                    <th>
                        @lang('messages.email')
                    </th>
                    <th>
                        @lang('messages.phone')
                    </th>
                    <th>
                        @lang('messages.alternate_phone')
                    </th>
                    <th>
                        @lang('messages.status')
                    </th>
                    <th>
                        @lang('messages.sell_do_date')
                    </th>
                    <th>
                        @lang('messages.sell_do_time')
                    </th>
                    <th>
                        @lang('messages.sell_do_lead_id')
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
            </thead>
        </table>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {

        if($(".date_range").length) {
            $('.date_range').daterangepicker({
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    cancelLabel: 'Clear'
                }
            });
        }
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        dtButtons.splice(4, 1);//remove excel button
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
            ajax: {
                url: "{{ route('admin.leads.index') }}",
                data: function (d) {
                    d.project_id = $("#project_id").val();
                    d.campaign_id = $("#campaign_id").val();
                    d.leads_status = $("#leads_status").val();
                    d.no_lead_id = $("#no_lead_id").is(":checked");
                    if($("#source_id").length) {
                        d.source = $("#source_id").val();
                    }
                    if($(".date_range").length) {
                        d.start_date = $('#added_on').data('daterangepicker').startDate.format('YYYY-MM-DD');
                        d.end_date = $('#added_on').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    }
                }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'ref_num', name: 'ref_num' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'secondary_phone', name: 'secondary_phone' },
                { data: 'overall_status', name: 'overall_status' },
                { data: 'sell_do_date', name: 'sell_do_date', orderable: false, searchable: false },
                { data: 'sell_do_time', name: 'sell_do_time', orderable: false, searchable: false },
                { data: 'sell_do_lead_id', name: 'sell_do_lead_id', orderable: false, searchable: false },
                { data: 'project_name', name: 'project.name' },
                { data: 'campaign_campaign_name', name: 'campaign.campaign_name' },
                { data: 'source_name', name: 'source.name' },
                { data: 'added_by', name: 'added_by' },
                { data: 'created_at', name: 'leads.created_at' },
                { data: 'updated_at', name: 'leads.updated_at' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 14, 'desc' ]],
            pageLength: 50,
        };
        let table = $('.datatable-Lead').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
            });
  
        let visibleColumnsIndexes = null;

        $(document).on('change', '#project_id, #campaign_id, #source_id, #added_on, #leads_status, #no_lead_id', function () {
            table.ajax.reload();
        });

        table.on('column-visibility.dt', function(e, settings, column, state) {
            visibleColumnsIndexes = []
            table.columns(":visible").every(function(colIdx) {
                visibleColumnsIndexes.push(colIdx);
            });
        })

        $(document).on('click', '#send_bulk_outgoing_webhook', function() {
            let selected_ids = $.map(table.rows({ selected: true }).data(), function (entry) {
                                    return entry.id;
                                });

            if (selected_ids.length === 0) {
                alert('{{ trans('global.datatables.zero_selected') }}')
                return
            }

            if (confirm('{{ trans('global.areYouSure') }}')) {
                $("#send_bulk_outgoing_webhook").attr('disabled', true);
                $.ajax({
                    method:"POST",
                    url:"{{route('admin.lead.send.mass.webhook')}}",
                    data:{
                        lead_ids:selected_ids
                    },
                    dataType: "JSON",
                    success: function(response) {
                        $("#send_bulk_outgoing_webhook").attr('disabled', false);
                        if(response.msg) {
                            alert(decodeURIComponent(response.msg));
                        }
                    }
                })
            }
        });

        function getProjectCampaigns() {
            $.ajax({
                method:"GET",
                url:"{{route('admin.projects.campaigns')}}",
                data:{
                    project_id:$("#project_id").val()
                },
                dataType: "html",
                success: function(response) {
                    $(".campaigns_div").html(response);
                }
            });
        }
        
        function getProjectAdditionalFields() {
            $(".additional_columns_to_export_div").hide();
            $.ajax({
                method:"GET",
                url:"{{route('admin.projects.additional.fields')}}",
                data:{
                    project_id:$("#project_id").val()
                },
                dataType: "html",
                success: function(response) {
                    $(".additional_columns_to_export_div").html(response).show();
                    $("#additional_columns_to_export").select2({
                        placeholder: "{{ trans('messages.please_select') }}"
                    });
                }
            });
        }

        $(document).on('change', '#project_id', function() {
            getProjectCampaigns();
            getProjectAdditionalFields();
        });

        $(document).on('change', '#campaign_id', function() {
            $.ajax({
                method:"GET",
                url:"{{route('admin.projects.campaign.sources')}}",
                data:{
                    project_id:$("#project_id").val(),
                    campaign_id:$("#campaign_id").val()
                },
                dataType: "html",
                success: function(response) {
                    $(".sources_div").html(response);
                }
            });
        });

        $(document).on('click', '#download_excel', function(){
            let filters = {};

            filters.project_id = $("#project_id").val();
            filters.campaign_id = $("#campaign_id").val();
            filters.leads_status = $("#leads_status").val();
            filters.no_lead_id = $("#no_lead_id").is(":checked");

            if($("#source_id").length) {
                filters.source = $("#source_id").val();
            }

            if($(".date_range").length) {
                filters.start_date = $('#added_on').data('daterangepicker').startDate.format('YYYY-MM-DD');
                filters.end_date = $('#added_on').data('daterangepicker').endDate.format('YYYY-MM-DD');
            }

            if($("#additional_columns_to_export").length) {
                filters.additional_columns = $("#additional_columns_to_export").val();
            }

            let url = "{{route('admin.leads.export')}}";

            const query = Object.keys(filters)
                            .map(key =>`${encodeURIComponent(key)}=${encodeURIComponent(filters[key])}`)
                            .join('&');

            if (query){
                url += `?${query}`;
            }
            window.open(url,'_blank');
        });
    });
</script>
@endsection