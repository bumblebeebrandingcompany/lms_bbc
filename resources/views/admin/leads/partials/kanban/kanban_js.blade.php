$(document).on('click', '#filter_leads', function() {
    let url = generateUrl("{{route('admin.leads.index')}}", 'kanban');
    sessionStorage.setItem("leadListUrl", url);
    window.location = url;
});

$(document).on('click', '#send_bulk_outgoing_webhook', function() {
    let selected_ids = [];
    
    $(".lead_ids").each(function(){
        if($(this).is(":checked")) {
            selected_ids.push($(this).val());
        }
    });

    if (selected_ids.length === 0) {
        alert('{{ trans('messages.no_leads_selected') }}')
        return
    }

    sendOutgoingWebhooks(selected_ids);
});