if($(".date_range").length) {
    @if(
        isset($filters['start_date']) &&
        isset($filters['end_date']) &&
        !empty($filters['start_date']) &&
        !empty($filters['end_date'])
    )
        const startDate = moment("{{$filters['start_date']}}") || moment().subtract(29, 'days');
        const endDate = moment("{{$filters['end_date']}}") || moment();
    @else
        const startDate = moment().subtract(59, 'days');
        const endDate = moment();
    @endif
    $('.date_range').daterangepicker({
        startDate: startDate,
        endDate: endDate,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'Last 60 Days': [moment().subtract(59, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'All Dates': [moment().subtract(100, 'years'), moment()] // Add option for all dates
        },
        locale: {
            cancelLabel: 'Clear'
        }
    });
}

function sendOutgoingWebhooks(selected_ids) {
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
}

@if($lead_view == 'list')
    @includeIf('admin.leads.partials.lead_table.lead_table_js')
@endif

/*
* filters
*/
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

$(document).on('change', '#parent_stage_id', function () {
    table.ajax.reload();
});
function generateUrl(url, view='') {
    let filters = {};

    filters.project_id = $("#project_id").val();
    filters.campaign_id = $("#campaign_id").val();
    filters.leads_status = $("#leads_status").val();
    filters.parent_stage_id = $("#parent_stage_id").val();
    filters.no_lead_id = $("#no_lead_id").is(":checked");

    if($("#source_id").length) {
        filters.source = $("#source_id").val();
    }
    if($("#parent_stage_id").length) {
        filters.parentStage = $("#parent_stage_id").val();
    }

    if($(".date_range").length) {
        filters.start_date = $('#added_on').data('daterangepicker').startDate.format('YYYY-MM-DD');
        filters.end_date = $('#added_on').data('daterangepicker').endDate.format('YYYY-MM-DD');
    }

    if($("#additional_columns_to_export").length) {
        filters.additional_columns = $("#additional_columns_to_export").val();
    }

    if(view) {
        filters.view = view;
    }

    // Add parent_stage_id filter
    if($("#parent_stage_id").length) {
        filters.parent_stage_id = $("#parent_stage_id").val();
    }

    const query = Object.keys(filters)
                    .map(key =>`${encodeURIComponent(key)}=${encodeURIComponent(filters[key])}`)
                    .join('&');

    if (query){
        url += `?${query}`;
    }

    return url;
}


$(document).on('click', '#download_excel', function(){
    let url = generateUrl("{{route('admin.leads.export')}}");
    window.open(url,'_blank');
});

/*
* toggle data view
*/
$(document).on('change', '.toggle_view', function() {
    let view = $(this).val();
    if(view === 'kanban') {
        let url = generateUrl("{{route('admin.leads.index')}}", view);
        sessionStorage.setItem("leadListUrl", url);
        window.location = url;
    } else {
        let url = "{{route('admin.leads.index')}}?view=list";
        sessionStorage.setItem("leadListUrl", url);
        window.location = url;
    }
});

@if($lead_view == 'kanban')
    @includeIf('admin.leads.partials.kanban.kanban_js')
@endif
