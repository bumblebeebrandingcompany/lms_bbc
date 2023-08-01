$(function() {
    function toggleAgencyAndClient(userType, resetValue=false) {

        $("#agency_id, #client_id, #projects").parent('div').hide();
        $("#agency_id, #client_id, #projects").prop('required', false);

        if(resetValue) {
            $("#agency_id, #client_id, #projects").val('').change();
        }

        if(userType == 'Client') {
            $("#client_id").parent('div').show();
            $("#client_id").prop('required', true);
        } else if(userType == 'Agency') {
            $("#agency_id").parent('div').show();
            $("#agency_id").prop('required', true);
        } else if(userType == 'ChannelPartner') {
            $("#projects").parent('div').show();
            $("#projects").prop('required', true);
        }
    }

    $(".user_type_input").on('change', function() {
        let userType = $(this).val();
        toggleAgencyAndClient(userType, true);
    });

    toggleAgencyAndClient($('.user_type_input:checked').val());
});