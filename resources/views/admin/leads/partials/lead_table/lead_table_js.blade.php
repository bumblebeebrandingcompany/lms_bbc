let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
dtButtons.splice(4, 1);//remove excel button
@if (auth()->user()->is_superadmin)
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
d.parent_stage_id = $("#parent_stage_id").val();

<!-- d.leads_status = $("#leads_status").val(); -->
<!-- d.no_lead_id = $("#no_lead_id").is(":checked"); -->
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
{ data: 'additional_email', name: 'additional_email' },
{ data: 'phone', name: 'phone' },
{ data: 'secondary_phone', name: 'secondary_phone' },
{ data: 'child_name', name: 'child_name' },
{ data: 'grade_enquired', name: 'grade_enquired' },
{ data: 'parent_stage_name', name: 'parent_stage_id' },
{ data: 'application_num', name: 'application_num' },
{ data: 'supervised_by', name: 'supervised_by' },
{ data: 'admission_team_name', name: 'admission_team_name' },


<!-- { data: 'overall_status', name: 'overall_status' }, -->
<!-- { data: 'sell_do_date', name: 'sell_do_date', orderable: false, searchable: false }, -->
<!-- { data: 'sell_do_time', name: 'sell_do_time', orderable: false, searchable: false }, -->
<!-- { data: 'sell_do_lead_id', name: 'sell_do_lead_id', orderable: false, searchable: false }, -->
{ data: 'campaign_campaign_name', name:'campaign.campaign_name' },
{ data: 'source_name', name: 'source.name' },
{ data: 'added_by', name: 'added_by' },
{ data: 'created_at', name: 'leads.created_at' },
{{-- { data: 'essential_fields', name: 'essential_fields' },
        { data: 'essential_fields', name: 'essential_fields' },
        { data: 'essential_fields', name: 'essential_fields' },
        { data: 'essential_fields', name: 'essential_fields' },
        { data: 'essential_fields', name: 'essential_fields' }, --}}
{ data: 'actions', name: '{{ trans('global.actions') }}' }
],
orderCellsTop: true,
    order: [[9, 'desc']],
    lengthMenu: [50, 100, 500, 1000], // Add 1000 to the lengthMenu options
    pageLength: 1000, // Set the page length to 1000
    drawCallback: function (settings) {
        let api = this.api();
        let pageInfo = api.page.info();
        $('.dataTables_info').html('Showing ' + (pageInfo.start + 1) + ' to ' + pageInfo.end + ' of ' + pageInfo.recordsTotal + ' entries');
    }
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

sendOutgoingWebhooks(selected_ids);
});
