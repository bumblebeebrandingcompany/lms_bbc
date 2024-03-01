let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
dtButtons.splice(4, 1); // Remove excel button

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
                headers: { 'x-csrf-token': _token },
                method: 'POST',
                url: config.url,
                data: { ids: ids, _method: 'DELETE' }
            })
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
            d.sub_source_id = $("#sub_source_id").val();
            d.stage_id = $("#stage_id").val();
            d.admission_team_name = $('#admission_team_name').val();
            d.supervised_by = $('#supervised_by').val()


            if ($("#source_id").length) {
                d.source_id = $("#source_id").val();
            }
            if ($(".date_range").length) {
                d.start_date = $('#added_on').data('daterangepicker').startDate.format('YYYY-MM-DD');
                d.end_date = $('#added_on').data('daterangepicker').endDate.format('YYYY-MM-DD');
            }


        }
    },
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        { data: 'ref_num', name: 'ref_num' },
        { data: 'father_name', name: 'father_name' },
        { data: 'email', name: 'email' },
        { data: 'secondary_email', name: 'secondary_email' },
        { data: 'phone', name: 'phone' },
        { data: 'secondary_phone', name: 'secondary_phone' },
        { data: 'child_name', name: 'child_name' },
        { data: 'grade_enquired', name: 'grade_enquired' },
        { data: 'parent_stage_name', name: 'stage_id' },
        { data: 'application_num', name: 'application_num' },
        { data: 'supervised_by', name: 'supervised_by' },
        { data: 'admission_team_name', name: 'admission_team_name' },
        { data: 'campaign_campaign_name', name: 'campaign.campaign_name' },
        { data: 'source_name', name: 'source.name' },
        { data: 'sub_source_name', name: 'subsource.name' },
        { data: 'added_by', name: 'added_by' },
        { data: 'created_at', name: 'leads.created_at' },
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
function updateLeadCount() {
    var leadCount = table.rows().count(); // Get the count of all rows in the DataTable without applying filters
    $('#leadCount').text('Total Leads: ' + leadCount); // Update the text of the lead count element
}

// Call updateLeadCount after table initialization
updateLeadCount();

// Call updateLeadCount after table is reloaded
table.on('draw', function () {
    updateLeadCount();
});

// Call updateLeadCount after any filtering or data change
$(document).on('change', '#project_id, #campaign_id, #source_id,#sub_source_id, #added_on, #leads_status, #no_lead_id, #parent_stage_id, #admission_team_name, #supervised_by', function () {
    table.ajax.reload(function () {
        updateLeadCount();
    });
});
$('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
    $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
});

let visibleColumnsIndexes = null;
table.on('column-visibility.dt', function (e, settings, column, state) {
    visibleColumnsIndexes = []
    table.columns(":visible").every(function (colIdx) {
        visibleColumnsIndexes.push(colIdx);
    });
});
$(document).on('click', '#send_bulk_outgoing_webhook', function () {
    let selected_ids = $.map(table.rows({ selected: true }).data(), function (entry) {
        return entry.id;
    });
    if (selected_ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')
        return
    }
    sendOutgoingWebhooks(selected_ids);
});
// Other functions such as getProjectCampaigns, getProjectAdditionalFields, etc. go here
