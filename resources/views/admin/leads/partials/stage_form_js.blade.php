<script>
    $(document).ready(function() {
        $('.edit-button').on('click', function() {
            // Show the edit fields and set border color to red
            $('.edit-field').show().css('border-color', 'red');
            $('.display-value').hide();
            $('.save-button').show();
            $('.edit-field').each(function() {
                var fieldName = $(this).attr('name');
                var displayValue = $(this).siblings('.display-value').text();
                $(this).val(displayValue);
            });
            // Clear any previous error messages
            $('.error-message').text('');
        });
        $('.save-button').on('click', function() {
            // Create an object to store the updated values
            var updatedValues = {};
            var isValid = true;
            // Update the display values with the entered values and store in the object
            $('.display-value').each(function() {
                var fieldName = $(this).attr('name');
                var updatedValue = $(this).siblings('.edit-field').val();

                // Perform validation for each field
                switch (fieldName) {
                    case 'secondary_email':
                        if (!isValidEmail(updatedValue)) {
                            isValid = false;
                            $('.error-message').text('Invalid email address.');
                        }
                        break;
                    case 'secondary_phone':
                        if (!isValidPhoneNumber(updatedValue)) {
                            isValid = false;
                            $('.error-message').text(
                                'Invalid phone number (10 digits required).');
                        }
                        break;
                    case 'intake_year':
                        // Add validation logic if needed
                        break;
                    case 'grade_enquired':
                        // Add validation logic if needed
                        break;

                    case 'previous_school':
                    case 'previous_school':
                        // Add validation logic if needed
                        break;
                        // Add more cases for other fields as needed
                }

                updatedValues[fieldName] = updatedValue;
            });

            // Check for empty fields
            if (updatedValue.trim() === '') {
                $(this).siblings('.edit-field').css('border-color', 'red');
            } else {
                $(this).siblings('.edit-field').css('border-color', 'blue');
            }

            // Add the _method field for Laravel to recognize it as a PUT request
            updatedValues['_method'] = 'PUT';

            // Add CSRF token
            updatedValues['_token'] = '{{ csrf_token() }}';

            // Check if all fields are valid before making the AJAX request
            if (isValid) {
                $.ajax({
                    method: 'POST',
                    url: '{{ route('admin.leads.update', [$lead->id]) }}',
                    data: updatedValues,
                    success: function(response) {
                        // Handle success if needed
                        console.log(response);
                        // Hide the text fields and show the display values
                        $('.edit-field').hide();
                        $('.display-value').show();
                    },
                    error: function(error) {
                        // Log the error response
                        console.error(error.responseJSON);
                        // Show the text fields and hide the display values
                        $('.edit-field').show();
                        $('.display-value').hide();
                    }
                });
            }
        });

    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function checkParentStageId(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var selectedName = selectedOption.text.trim().toLowerCase();

        // Hide all modals initially

        document.getElementById('showfollowup').style.display = 'none';
        document.getElementById('showrescheduled').style.display = 'none';
        document.getElementById('showsitevisitScheduled').style.display = 'none';
        document.getElementById('showadmissionfollowup').style.display = 'none';
        document.getElementById('notvisitedContent').style.display = 'none';
        document.getElementById('spamContent').style.display = 'none';
        document.getElementById('notqualifiedContent').style.display = 'none';
        document.getElementById('lostContent').style.display = 'none';
        document.getElementById('futureprospectContent').style.display = 'none';
        document.getElementById('cancelledContent').style.display = 'none';
        document.getElementById('rnrContent').style.display = 'none';
        document.getElementById('sitevisitconductedContent').style.display = 'none';
        document.getElementById('applicationpurchasedContent').style.display = 'none';
        document.getElementById('AdmissionContent').style.display = 'none';
        document.getElementById('applicationnotpurchasedContent').style.display = 'none';
        document.getElementById('admissionwithdrawnContent').style.display = 'none';
        // Check the selected option and display the corresponding modal
        if (selectedName === 'followup') {
            $("#showfollowup").show();
        } else if (selectedName === 'site visit scheduled') {
            $("#showsitevisitScheduled").show();
        } else if (selectedName === 'admission followup') {
            $("#showadmissionfollowup").show();
        } else if (selectedName === 'rescheduled') {
            $("#showrescheduled").show();
        } else if (selectedName === 'site not visited') {
            document.getElementById('notvisitedContent').style.display = 'block';
        } else if (selectedName === 'spam') {
            document.getElementById('spamContent').style.display = 'block';
        } else if (selectedName === 'not qualified') {
            document.getElementById('notqualifiedContent').style.display = 'block';
        } else if (selectedName === 'lost') {
            document.getElementById('lostContent').style.display = 'block';
        } else if (selectedName === 'future prospect') {
            document.getElementById('futureprospectContent').style.display = 'block';
        } else if (selectedName === 'cancelled') {
            document.getElementById('cancelledContent').style.display = 'block';
        } else if (selectedName === 'rnr') {
            document.getElementById('rnrContent').style.display = 'block';
        } else if (selectedName === 'site visit conducted') {
            document.getElementById('sitevisitconductedContent').style.display = 'block';
        } else if (selectedName === 'application purchased') {
            document.getElementById('applicationpurchasedContent').style.display = 'block';
        } else if (selectedName === 'admitted') {
            document.getElementById('AdmissionContent').style.display = 'block';
        } else if (selectedName === 'application not purchased') {
            document.getElementById('applicationnotpurchasedContent').style.display = 'block';
        } else if (selectedName === 'admission withdrawn') {
            document.getElementById('admissionwithdrawnContent').style.display = 'block';
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        checkParentStageId(document.getElementById('stage_id'));
    });

    $(document).ready(function() {
        $('#tag_id').change(function() {
            var selectedTagId = $('#tag_id option:selected').val();
            // Check if childStages is not null
            var childStages = {!! json_encode($lead->parentStage->childStages ?? null) !!};
            console.log("Child Stages:", childStages);
            var selectedChildStages = [];

            // Check if childStages is not null and has the selected_child_stages property
            if (childStages && childStages.length > 0 && childStages[0].selected_child_stages) {
                selectedChildStages = JSON.parse(childStages[0].selected_child_stages);
            }

            console.log("Selected Child Stages:", selectedChildStages);

            $('#child_stage_id').html(
                '<option value="" selected disabled>Please Select a Tag First</option>');

            if (selectedTagId && selectedChildStages) {
                // Filter child stages based on the selected tag and lead's selected_child_stages
                @foreach ($parentStages as $stage)
                    if ("{{ $stage->tag_id }}" == selectedTagId && selectedChildStages.includes(
                            "{{ $stage->id }}")) {
                        $('#child_stage_id').append(
                            '<option value="{{ $stage->id }}" data-tag="{{ $stage->tag_id }}">{{ $stage->name }}</option>'
                        );
                    }
                @endforeach
            }
        });
    });
</script>
