@extends('layouts.admin')
@section('content')
<!-- Inside your blade view -->



    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Create Walkin</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.walkinform.store') }}" method="post">
                        @csrf

                        <div class="form-group" class="required">
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group" class="required">
                            <label for="phone">Phone:</label>
                            <input type="text" name="phone" id="phone"
                                value="{{ old('phone') ? old('phone') : $phone ?? '' }}" class="form-control input_number"
                                @if (!auth()->user()->is_superadmin) required @endif>
                        </div>
                        <div class="form-group" class="required">
                            <label for="secondary_phone">Secondary Phone:</label>
                            <input type="phone" name="secondary_phone" class="form-control" value="{{ old('secondary_phone') ? old('secondary_phone') : $secondary_phone ?? '' }}" required>
                        </div>
                        <div class="form-group" class="required">
                            <label for="email">Email:</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') ? old('email') : $email ?? '' }}" required>
                        </div>
                        <div class="form-group" class="required">
                            <label for="additional_email">Additional Email:</label>
                            <input type="email" name="additional_email" class="form-control" value="{{ old('additional_email') ? old('additional_email') : $additional_email ?? '' }}" required>
                        </div>

                        <input type="hidden" name="comments" class="form-control" value= "Direct Walk-in attended"
                            required>
                        @if (!(auth()->user()->is_agency || auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager))
                        <div class="form-group">
                            <label class="required" for="promo_id">SubSource</label>
                            <select class="form-control {{ $errors->has('promo_id') ? 'is-invalid' : '' }}" name="promo_id" id="promo_id" required>
                                <option value="">Select SubSource</option>
                                @foreach($promos as $subsource)
                                    <option value="{{ $subsource->id }}" {{ old('promo_id') == $subsource->id ? 'selected' : '' }}>{{ $subsource->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('promo_id'))
                                <span class="text-danger">{{ $errors->first('promo_id') }}</span>
                            @endif
                            <span class="help-block">Select the subsource associated with the walk-in</span>
                        </div>
                        @if ($errors->any())
                        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="errorModalLabel">Error</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <ul>
                                            @foreach ($leads as $lead)
                                            <div id="existingLeadDetails_{{ $lead->id }}">
                                                <p>Lead ID: {{ $lead->ref_num }}</p> <!-- Add more details as needed -->
                                            </div>
                                        @endforeach
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                            <br>
                        @endif
                        <button type="submit" class="btn btn-success">Create Walkin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {
            function getCampaigns() {
                let data = {
                    project_id: $('#project_id').val()
                };

                $.ajax({
                    method: "GET",
                    url: "{{ route('admin.get.campaigns') }}",
                    data: data,
                    dataType: "json",
                    success: function(response) {
                        $('#campaign_id').select2('destroy').empty().select2({
                            data: response
                        });

                        getSource();
                    }
                });
            }
            function getSource() {
    let data = {
        project_id: $('#project_id').val(),
        campaign_id: $('#campaign_id').val(),
    };

    $.ajax({
        method: "GET",
        url: "{{ route('admin.get.sources') }}",
        data: data,
        dataType: "json",
        success: function(response) {
            // Remove option with value '25'
            response = response.filter(function(item) {
                return item.id !== 25;
            });
            // Initialize Select2 after removing the option
            $('#source_id').select2('destroy').empty().select2({
                data: response
            });
        }
    });
}

            $(document).on('change', '#project_id', function() {
                getCampaigns();
                getLeadDetailsRowHtml();
            });
            $(document).on('change', '#campaign_id', function() {
                getSource();
            });
            $(document).on('click', '.add_lead_detail', function() {
                let index = $("#index_count").val();
                $.ajax({
                    method: "GET",
                    url: "{{ route('admin.lead.detail.html') }}",
                    data: {
                        index: index
                    },
                    dataType: "html",
                    success: function(response) {
                        $("div.lead_details").append(response);
                        $("#index_count").val(+index + 1);
                    }
                });
            });

            $(document).on('click', '.delete_lead_detail_row', function() {
                if (confirm('Do you want to remove?')) {
                    $(this).closest('.row').remove();
                }
            });

            function getLeadDetailsRowHtml() {
                $.ajax({
                    method: "GET",
                    url: "{{ route('admin.lead.details.rows') }}",
                    data: {
                        project_id: $('#project_id').val(),
                        lead_id: $('#lead_id').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        $("div.lead_details").html(response.html);
                        $("#index_count").val(response.count);
                    }
                });
            }

            $(document).on('click', '.add_prefilled_lead_detail', function() {
                let index = $("#index_count").val();
                $.ajax({
                    method: "GET",
                    url: "{{ route('admin.lead.detail.html') }}",
                    data: {
                        index: index,
                        project_id: $('#project_id').val()
                    },
                    dataType: "html",
                    success: function(response) {
                        $("div.lead_details").append(response);
                        $("#index_count").val(+index + 1);
                        $(".select-tags").select2();
                    }
                });
            });
            getCampaigns();
        });

    </script>
<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@if ($errors->any())
    <script>
        $(document).ready(function () {
            $('#errorModal').modal('show');
        });
    </script>
@endif

@endsection


    <script>
        $(function () {

            $(document).on('change', '#phone, #email', function () {
                let phone = $('#phone').val();
                let email = $('#email').val();
                $.ajax({
                    method: "GET",
                    url: "{{ route('admin.lead.details.rows') }}",
                    data: { phone: phone, email: email },
                    dataType: "json",
                    success: function (response) {
                        // Display the leads in the modal
                        $('#errorModal .modal-body ul').empty();
                        if (response.length > 0) {
                            $.each(response, function (index, lead) {
                                $('#errorModal .modal-body ul').append('<li>Lead ID: ' + lead.id + '</li>');
                            });

                            $('#errorModal').modal('show');
                        }
                    }
                });
            });
        });
    </script>
