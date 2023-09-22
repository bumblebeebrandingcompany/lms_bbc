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
        @includeIf('admin.leads.partials.lead_table.lead_table')
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
        @includeIf('admin.leads.partials.lead_table.lead_table_js')
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