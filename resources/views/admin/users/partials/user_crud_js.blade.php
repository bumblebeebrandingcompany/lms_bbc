$(function() {
    function toggleAgencyAndClient(userType, resetValue=false) {

        $("#agency_id, #client_id, #projects, #assign_client").parent('div').hide();
        $("#agency_id, #client_id, #projects, #assign_client").prop('required', false);

        if(resetValue) {
            $("#agency_id, #client_id, #projects, #assign_client").val('').change();
        }

        if(userType == 'Clients') {
            $("#client_id").parent('div').show();
            $("#client_id").prop('required', true);
        } else if(userType == 'Agency') {
            $("#agency_id").parent('div').show();
            $("#agency_id").prop('required', true);
        } else if(userType == 'Presales') {
            $("#agency_id").parent('div').show();
            $("#agency_id").prop('required', true);
        } else if(userType == 'Frontoffice') {
            $("#client_id").parent('div').show();
            $("#client_id").prop('required', true);
        }
        else if(userType == 'Admissionteam') {
            $("#client_id").parent('div').show();
            $("#client_id").prop('required', true);
        }
    }

    $(".user_type_input").on('change', function() {
        let userType = $(this).val();
        toggleAgencyAndClient(userType, true);
    });

    toggleAgencyAndClient($('.user_type_input:checked').val());
});
